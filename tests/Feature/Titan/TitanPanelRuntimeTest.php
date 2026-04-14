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

    public function test_titan_panel_access_requires_tenant_and_admin_or_titan_permission(): void
    {
        auth()->logout();
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, ['employee'], false));
        $this->assertFalse(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, ['admin'], false));
        $this->assertTrue(TitanPanelProvider::canAccess());

        auth()->setUser(new TitanRuntimeFakeUser(1, ['employee'], 'all'));
        $this->assertTrue(TitanPanelProvider::canAccess());
    }

}

class TitanRuntimeFakeUser extends AuthenticatableUser
{
    public ?int $company_id = null;

    /** @var array<int, string> */
    private array $roles = [];

    private string|bool $titanPermission;

    /**
     * @param array<int, string> $roles
     */
    public function __construct(?int $companyId = null, array $roles = [], string|bool $titanPermission = false)
    {
        parent::__construct([]);
        $this->company_id = $companyId;
        $this->roles = $roles;
        $this->titanPermission = $titanPermission;
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
