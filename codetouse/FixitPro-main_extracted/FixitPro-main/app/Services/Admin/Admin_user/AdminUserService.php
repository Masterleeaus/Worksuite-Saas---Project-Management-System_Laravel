<?php

namespace App\Services\Admin\Admin_user;

use App\Models\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminUserService
{
    public function getAllAdmins(): Collection
    {
        return Admin::latest()->get();
    }

    public function createAdmin($request, array $data): Admin
    {
        $profile_image = \App\Components\UploadAttachmentComponent::uploadAttachment(
            $request, 
            'profile_image', 
            'admins', 
            ['jpg', 'jpeg', 'png', 'svg', 'gif']
        );

        if ($profile_image) {
            $data['profile_image'] = 'storage/admins/' . $profile_image;
        } else {
            // Unset if it was part of the validated data but no file was uploaded/processed
            unset($data['profile_image']);
        }

        $data['password'] = Hash::make($data['password']);
        
        // Handle Role Assignment
        $roleId = $data['role_id'] ?? null;
        if ($roleId) {
            $role = \App\Models\Acl\Role::find($roleId);
            if ($role) {
                $data['designation'] = $role->role_name;
            }
        }
        unset($data['role_id']);

        $admin = Admin::create($data);

        if ($roleId) {
            \App\Models\Acl\AdminUserRole::create([
                'admin_ID' => $admin->id,
                'role_ID' => $roleId
            ]);
        }

        return $admin;
    }

    public function updateAdmin(Admin $admin, $request, array $data): bool
    {
        $profile_image = \App\Components\UploadAttachmentComponent::uploadAttachment(
            $request, 
            'profile_image', 
            'admins', 
            ['jpg', 'jpeg', 'png', 'svg', 'gif']
        );

        if ($profile_image) {
            // Delete old image if exists in storage
            if ($admin->profile_image) {
                $oldPath = str_replace('storage/', '', $admin->profile_image);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $data['profile_image'] = 'storage/admins/' . $profile_image;
        } else {
            // Ensure we don't try to save the file object if present in the data array
            unset($data['profile_image']);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle Role Assignment
        $roleId = $data['role_id'] ?? null;
        if ($roleId) {
            $role = \App\Models\Acl\Role::find($roleId);
            if ($role) {
                $data['designation'] = $role->role_name;
            }
            
            // For simplicity, we'll delete old roles and create a new one (since user requested 1 designation/role)
            \App\Models\Acl\AdminUserRole::where('admin_ID', $admin->id)->delete();
            \App\Models\Acl\AdminUserRole::create([
                'admin_ID' => $admin->id,
                'role_ID' => $roleId
            ]);
        }
        unset($data['role_id']);

        return $admin->update($data);
    }

    public function deleteAdmin(Admin $admin): bool
    {
        // Prevent deleting the last admin or the currently logged in admin (optional logic)
        return $admin->delete();
    }
}
