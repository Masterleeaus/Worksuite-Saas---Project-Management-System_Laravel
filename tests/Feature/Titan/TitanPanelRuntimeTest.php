<?php

namespace Tests\Feature\Titan;

use App\Http\Middleware\FilamentAuthenticate;
use App\Providers\TitanPanelProvider;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Http\Request;
use ReflectionMethod;
use Tests\TestCase;

class TitanPanelRuntimeTest extends TestCase
{
    public function test_titan_auth_middleware_redirects_to_worksuite_login(): void
    {
        $middleware = app(FilamentAuthenticate::class);
        $method = new ReflectionMethod($middleware, 'redirectTo');
        $method->setAccessible(true);

        $request = Request::create('/titan', 'GET');

        $this->assertSame(route('login'), $method->invoke($middleware, $request));
    }

    public function test_titan_panel_access_gate_matches_required_roles_and_permissions(): void
    {
        auth()->logout();
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(null, [], false, true));
        $this->assertTrue(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, ['employee'], false));
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, [], false));
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, ['admin'], false));
        $this->assertTrue(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, ['employee'], 'all'));
        $this->assertTrue(TitanPanelProvider::canAccess());
    }

    public function test_titan_panel_access_resolves_wrapped_worksuite_user_from_auth_user(): void
    {
        $worksuiteUser = new TitanRuntimeFakeUser(1, ['admin'], false);
        auth()->setUser(new TitanRuntimeAuthWrapper($worksuiteUser));

        $this->assertTrue(TitanPanelProvider::canAccess());
    }

}

class TitanRuntimeFakeUser extends AuthenticatableUser
{
    public ?int $company_id = null;
    public int $is_superadmin = 0;
    public ?object $company = null;

    /** @var array<int, string> */
    private array $roles = [];

    private string|bool $titanPermission;

    /**
     * @param array<int, string> $roles
     */
    public function __construct(?int $companyId = null, array $roles = [], string|bool $titanPermission = false, bool $isSuperadmin = false)
    {
        parent::__construct([]);
        $this->company_id = $companyId;
        $this->company = $companyId ? (object) ['id' => $companyId] : null;
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
        if ($permission !== 'titan_access') {
            return false;
        }

        return $this->titanPermission;
    }
}

class TitanRuntimeAuthWrapper extends AuthenticatableUser
{
    public ?object $user = null;

    public function __construct(?object $worksuiteUser = null)
    {
        parent::__construct([]);
        $this->user = $worksuiteUser;
    }
}
