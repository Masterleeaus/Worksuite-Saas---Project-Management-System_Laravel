<?php

namespace Tests\Feature\Titan;

use App\Filament\Resources\BaseTenantResource;
use App\Filament\Resources\DocumentTemplateResource;
use App\Http\Middleware\ApplyTitanTenantScope;
use App\Http\Middleware\EnsureTitanPanelAccess;
use App\Providers\TitanPanelProvider;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanDocs\Entities\DocumentTemplate;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class TitanTenantIsolationDatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::dropAllTables();

        Schema::create('companies', function ($table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('document_templates', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('template_type');
            $table->string('document_type');
            $table->longText('html_content');
            $table->json('required_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_global')->default(false);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function test_tenant_scoped_query_hides_cross_tenant_templates_and_blocks_cross_tenant_mutation(): void
    {
        DB::table('companies')->insert([
            ['id' => 1, 'company_name' => 'Tenant A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_name' => 'Tenant B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            ['id' => 101, 'name' => 'Admin A', 'email' => 'admin-a@example.test', 'company_id' => 1, 'password' => 'x', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 202, 'name' => 'Admin B', 'email' => 'admin-b@example.test', 'company_id' => 2, 'password' => 'x', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $templateA = DocumentTemplate::query()->create([
            'name' => 'A Template',
            'template_type' => 'client',
            'document_type' => 'proposal',
            'html_content' => '<p>A</p>',
            'company_id' => 1,
            'created_by' => 101,
            'is_active' => true,
            'is_approved' => true,
        ]);

        $templateB = DocumentTemplate::query()->create([
            'name' => 'B Template',
            'template_type' => 'client',
            'document_type' => 'proposal',
            'html_content' => '<p>B</p>',
            'company_id' => 2,
            'created_by' => 202,
            'is_active' => true,
            'is_approved' => true,
        ]);

        auth()->setUser(new TitanDbRuntimeUser(101, 1, ['admin'], false));
        $adminATemplates = DocumentTemplateResource::getEloquentQuery()->pluck('id')->all();
        $this->assertContains($templateA->id, $adminATemplates);
        $this->assertNotContains($templateB->id, $adminATemplates);
        $this->assertNull(DocumentTemplateResource::getEloquentQuery()->find($templateB->id));
        $this->assertSame(0, DocumentTemplateResource::getEloquentQuery()->whereKey($templateB->id)->update(['name' => 'Blocked update']));
        $this->assertSame(0, DocumentTemplateResource::getEloquentQuery()->whereKey($templateB->id)->delete());
        $this->assertSame(1, DocumentTemplateResource::getEloquentQuery()->whereKey($templateA->id)->update(['name' => 'Allowed update A']));

        auth()->setUser(new TitanDbRuntimeUser(202, 2, ['admin'], false));
        $adminBTemplates = DocumentTemplateResource::getEloquentQuery()->pluck('id')->all();
        $this->assertContains($templateB->id, $adminBTemplates);
        $this->assertNotContains($templateA->id, $adminBTemplates);
        $this->assertNull(DocumentTemplateResource::getEloquentQuery()->find($templateA->id));
        $this->assertSame(0, DocumentTemplateResource::getEloquentQuery()->whereKey($templateA->id)->update(['name' => 'Blocked update']));
        $this->assertSame(0, DocumentTemplateResource::getEloquentQuery()->whereKey($templateA->id)->delete());
        $this->assertSame(1, DocumentTemplateResource::getEloquentQuery()->whereKey($templateB->id)->update(['name' => 'Allowed update B']));
    }

    public function test_stamp_tenant_data_sets_company_and_creator_for_runtime_create_flow(): void
    {
        auth()->setUser(new TitanDbRuntimeUser(101, 1, ['admin'], false));

        $data = BaseTenantResource::stampTenantData([
            'name' => 'Runtime Created',
            'template_type' => 'client',
            'document_type' => 'proposal',
        ]);

        $this->assertSame(1, $data['company_id']);
        $this->assertSame(101, $data['created_by']);
    }

    public function test_auth_and_tenant_middlewares_enforce_guest_employee_admin_and_no_company_cases(): void
    {
        $request = Request::create('/titan/document-templates', 'GET');
        $tenantMiddleware = app(ApplyTitanTenantScope::class);
        $accessMiddleware = app(EnsureTitanPanelAccess::class);
        $next = fn () => response('ok', Response::HTTP_OK);

        auth()->logout();
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(301, 1, ['employee'], false));
        $tenantMiddleware->handle($request, $next);
        $this->assertSame(403, $this->statusFromHttpException(fn () => $accessMiddleware->handle($request, $next)));

        auth()->setUser(new TitanDbRuntimeUser(101, 1, ['admin'], false));
        $tenantResponse = $tenantMiddleware->handle($request, $next);
        $this->assertSame(Response::HTTP_OK, $tenantResponse->status());
        $accessResponse = $accessMiddleware->handle($request, $next);
        $this->assertSame(Response::HTTP_OK, $accessResponse->status());

        auth()->setUser(new TitanDbRuntimeUser(901, null, [], false, true));
        $superadminTenantResponse = $tenantMiddleware->handle($request, $next);
        $this->assertSame(Response::HTTP_OK, $superadminTenantResponse->status());
        $superadminAccessResponse = $accessMiddleware->handle($request, $next);
        $this->assertSame(Response::HTTP_OK, $superadminAccessResponse->status());

        auth()->setUser(new TitanDbRuntimeUser(401, null, ['admin'], false));
        $this->assertSame(403, $this->statusFromHttpException(fn () => $tenantMiddleware->handle($request, $next)));
    }

    public function test_tenant_middleware_allows_valid_company_id_when_company_relation_is_null(): void
    {
        DB::table('companies')->insert([
            'id' => 3,
            'company_name' => 'Tenant C',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request = Request::create('/titan/document-templates', 'GET');
        $tenantMiddleware = app(ApplyTitanTenantScope::class);
        $next = fn () => response('ok', Response::HTTP_OK);

        auth()->setUser(new TitanDbRuntimeUser(701, 3, ['admin'], titanPermission: false, isSuperadmin: false, withCompanyRelation: false));
        $tenantResponse = $tenantMiddleware->handle($request, $next);

        $this->assertSame(Response::HTTP_OK, $tenantResponse->status());
        $this->assertSame(3, $request->attributes->get('titan_company_id'));
    }

    public function test_titan_access_gate_matrix_is_correct_for_runtime_users(): void
    {
        auth()->logout();
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(501, 1, ['employee'], false));
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(502, 1, ['employee'], false));
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(507, 1, [], false));
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(503, 1, ['admin'], false));
        $this->assertTrue(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(504, null, [], false, true));
        $this->assertTrue(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(505, 1, ['employee'], 'all'));
        $this->assertTrue(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanDbRuntimeUser(506, null, ['admin'], false));
        $this->assertFalse(TitanPanelProvider::canAccess());
    }

    /**
     * @param  callable(): mixed  $callback
     */
    private function statusFromHttpException(callable $callback): int
    {
        try {
            $callback();
        } catch (HttpException $e) {
            return $e->getStatusCode();
        }

        $this->fail('Expected HttpException was not thrown.');
    }
}

class TitanDbRuntimeUser extends AuthenticatableUser
{
    public ?int $company_id = null;
    public ?object $company = null;
    public int $is_superadmin = 0;

    /** @var array<int, string> */
    private array $roles = [];

    private string|bool $titanPermission = false;

    /**
     * @param  array<int, string>  $roles
     */
    public function __construct(
        ?int $id = null,
        ?int $companyId = null,
        array $roles = [],
        string|bool $titanPermission = false,
        bool $isSuperadmin = false,
        bool $withCompanyRelation = true
    )
    {
        parent::__construct([]);
        $this->id = $id ?? 0;
        $this->company_id = $companyId;
        $this->company = ($companyId === null || !$withCompanyRelation) ? null : (object) ['id' => $companyId];
        $this->roles = $roles;
        $this->titanPermission = $titanPermission;
        $this->is_superadmin = $isSuperadmin ? 1 : 0;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    public function permission(string $permission): string|bool
    {
        return $permission === 'titan_access' ? $this->titanPermission : false;
    }
}
