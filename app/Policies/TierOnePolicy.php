<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

abstract class TierOnePolicy
{
    protected string $viewPermission;
    protected string $addPermission;
    protected string $editPermission;
    protected string $deletePermission;

    public function before($user, $ability): ?bool
    {
        if (!$user) {
            return false;
        }

        if ((int) ($user->is_superadmin ?? 0) === 1) {
            return true;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('client')) {
            return false;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return !empty($user->company_id);
        }

        return null;
    }

    public function viewAny($user): bool
    {
        return $this->hasAnyAccess($user, $this->viewPermission);
    }

    public function view($user, Model $record): bool
    {
        return $this->checkRecordAccess($user, $record, $this->viewPermission);
    }

    public function create($user): bool
    {
        return $this->hasAnyAccess($user, $this->addPermission);
    }

    public function update($user, Model $record): bool
    {
        return $this->checkRecordAccess($user, $record, $this->editPermission);
    }

    public function delete($user, Model $record): bool
    {
        return $this->checkRecordAccess($user, $record, $this->deletePermission);
    }

    public function deleteAny($user): bool
    {
        return $this->hasAnyAccess($user, $this->deletePermission);
    }

    protected function hasAnyAccess($user, string $permissionName): bool
    {
        if (!method_exists($user, 'permission')) {
            return false;
        }

        return !in_array($user->permission($permissionName), [false, null, 'none'], true);
    }

    protected function checkRecordAccess($user, Model $record, string $permissionName): bool
    {
        if (!$this->sameTenant($user, $record)) {
            return false;
        }

        if (!method_exists($user, 'permission')) {
            return false;
        }

        $permission = $user->permission($permissionName);

        if ($permission === 'all') {
            return true;
        }

        if ($permission === 'added') {
            return (int) ($record->added_by ?? 0) === (int) $user->id;
        }

        if ($permission === 'owned') {
            return $this->isOwnedByUser($user, $record);
        }

        if ($permission === 'both') {
            return ((int) ($record->added_by ?? 0) === (int) $user->id)
                || $this->isOwnedByUser($user, $record);
        }

        return false;
    }

    protected function sameTenant($user, Model $record): bool
    {
        if (!isset($record->company_id) || empty($user->company_id)) {
            return true;
        }

        return (int) $record->company_id === (int) $user->company_id;
    }

    protected function isOwnedByUser($user, Model $record): bool
    {
        $userId = (int) $user->id;

        if (isset($record->client_id) && (int) $record->client_id === $userId) {
            return true;
        }

        if (isset($record->created_by) && (int) $record->created_by === $userId) {
            return true;
        }

        if (isset($record->lead_owner) && (int) $record->lead_owner === $userId) {
            return true;
        }

        if (method_exists($record, 'users')) {
            try {
                return $record->users()->where('users.id', $userId)->exists();
            } catch (\Throwable $e) {
                return false;
            }
        }

        return false;
    }
}
