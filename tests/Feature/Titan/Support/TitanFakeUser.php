<?php

namespace Tests\Feature\Titan\Support;

use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class TitanFakeUser extends AuthenticatableUser
{
    public ?int $company_id = null;
    public ?object $company = null;

    /** @var array<int, string> */
    private array $roles = [];

    /** @var array<string, string|bool> */
    private array $permissions = [];

    /**
     * @param  array<int, string>  $roles
     * @param  array<string, string|bool>  $permissions
     */
    public function __construct(int $id = 0, ?int $companyId = null, array $roles = [], array $permissions = [])
    {
        parent::__construct([]);
        $this->id = $id;
        $this->company_id = $companyId;
        $this->company = $companyId === null ? null : (object) ['id' => $companyId];
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    public function permission(string $permission): string|bool
    {
        return $this->permissions[$permission] ?? false;
    }
}
