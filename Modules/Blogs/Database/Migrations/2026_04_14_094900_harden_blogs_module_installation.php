<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $moduleName = 'blogs';

    public function up(): void
    {
        $moduleId = $this->registerModule();

        $this->repairBlogTables();
        $this->seedPermissions($moduleId);
        $this->seedModuleSettings();
        $this->syncPackages();
    }

    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->whereIn('name', [
                'view_blogs',
                'create_blogs',
                'edit_blogs',
                'delete_blogs',
                'manage_blogs',
                'publish_blogs',
                'export_blogs',
            ])->delete();
        }

        if (Schema::hasTable('module_settings')) {
            DB::table('module_settings')->where('module_name', $this->moduleName)->delete();
        }

        if (Schema::hasTable('modules')) {
            DB::table('modules')->where('module_name', $this->moduleName)->delete();
        }
    }

    private function registerModule(): ?int
    {
        if (!Schema::hasTable('modules')) {
            return null;
        }

        $moduleColumns = Schema::getColumnListing('modules');
        $modulePayload = ['module_name' => $this->moduleName];

        if (in_array('description', $moduleColumns, true)) {
            $modulePayload['description'] = 'Blogs module';
        }

        if (in_array('is_superadmin', $moduleColumns, true)) {
            $modulePayload['is_superadmin'] = 0;
        }

        $existing = DB::table('modules')->where('module_name', $this->moduleName)->first();

        if ($existing) {
            DB::table('modules')->where('module_name', $this->moduleName)->update($modulePayload);
            return (int) $existing->id;
        }

        $modulePayload['created_at'] = now();
        $modulePayload['updated_at'] = now();

        return (int) DB::table('modules')->insertGetId($modulePayload);
    }

    private function repairBlogTables(): void
    {
        if (Schema::hasTable('blog_categories')) {
            $this->addColumnIfMissing('blog_categories', 'company_id', fn (Blueprint $table) => $table->unsignedInteger('company_id')->nullable()->index());
            $this->addColumnIfMissing('blog_categories', 'language_id', fn (Blueprint $table) => $table->unsignedInteger('language_id')->nullable()->index());
            $this->addColumnIfMissing('blog_categories', 'parent_id', fn (Blueprint $table) => $table->unsignedBigInteger('parent_id')->default(0)->index());
            $this->addColumnIfMissing('blog_categories', 'created_by', fn (Blueprint $table) => $table->unsignedInteger('created_by')->nullable()->index());
            $this->addColumnIfMissing('blog_categories', 'updated_by', fn (Blueprint $table) => $table->unsignedInteger('updated_by')->nullable()->index());
        }

        if (Schema::hasTable('blog_posts')) {
            $this->addColumnIfMissing('blog_posts', 'company_id', fn (Blueprint $table) => $table->unsignedInteger('company_id')->nullable()->index());
            $this->addColumnIfMissing('blog_posts', 'language_id', fn (Blueprint $table) => $table->unsignedInteger('language_id')->nullable()->index());
            $this->addColumnIfMissing('blog_posts', 'parent_id', fn (Blueprint $table) => $table->unsignedBigInteger('parent_id')->default(0)->index());
            $this->addColumnIfMissing('blog_posts', 'created_by', fn (Blueprint $table) => $table->unsignedInteger('created_by')->nullable()->index());
            $this->addColumnIfMissing('blog_posts', 'updated_by', fn (Blueprint $table) => $table->unsignedInteger('updated_by')->nullable()->index());
        }

        if (Schema::hasTable('blog_comments')) {
            $this->addColumnIfMissing('blog_comments', 'company_id', fn (Blueprint $table) => $table->unsignedInteger('company_id')->nullable()->index());
            $this->addColumnIfMissing('blog_comments', 'post_id', fn (Blueprint $table) => $table->unsignedBigInteger('post_id')->nullable()->index());
            $this->addColumnIfMissing('blog_comments', 'user_id', fn (Blueprint $table) => $table->unsignedInteger('user_id')->nullable()->index());
            $this->addColumnIfMissing('blog_comments', 'comment', fn (Blueprint $table) => $table->text('comment')->nullable());
        }
    }

    private function seedPermissions(?int $moduleId): void
    {
        if (!$moduleId || !Schema::hasTable('permissions')) {
            return;
        }

        $permissionNames = [
            'view_blogs' => 'View Blogs',
            'create_blogs' => 'Create Blogs',
            'edit_blogs' => 'Edit Blogs',
            'delete_blogs' => 'Delete Blogs',
            'manage_blogs' => 'Manage Blogs',
            'publish_blogs' => 'Publish Blogs',
            'export_blogs' => 'Export Blogs',
        ];

        foreach ($permissionNames as $name => $displayName) {
            $payload = [
                'name' => $name,
                'module_id' => $moduleId,
                'display_name' => $displayName,
                'allowed_permissions' => '{"all":4, "none":5}',
                'is_custom' => 1,
                'updated_at' => now(),
            ];

            $existing = DB::table('permissions')->where('name', $name)->first();

            if ($existing) {
                DB::table('permissions')->where('id', $existing->id)->update($payload);
                $permissionId = (int) $existing->id;
            } else {
                $payload['created_at'] = now();
                $permissionId = (int) DB::table('permissions')->insertGetId($payload);
            }

            $this->attachPermissionToAdminRoles($permissionId);
        }
    }

    private function attachPermissionToAdminRoles(int $permissionId): void
    {
        if (!Schema::hasTable('permission_role') || !Schema::hasTable('roles')) {
            return;
        }

        $roleIds = DB::table('roles')->where('name', 'admin')->pluck('id');

        foreach ($roleIds as $roleId) {
            $exists = DB::table('permission_role')
                ->where('permission_id', $permissionId)
                ->where('role_id', $roleId)
                ->exists();

            if (!$exists) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                    'permission_type_id' => 4,
                ]);
            }
        }
    }

    private function seedModuleSettings(): void
    {
        if (!Schema::hasTable('module_settings')) {
            return;
        }

        $types = ['admin', 'employee', 'client'];
        $settingColumns = Schema::getColumnListing('module_settings');
        $companyIds = [null];

        if (Schema::hasTable('companies') && in_array('company_id', $settingColumns, true)) {
            $companyIds = DB::table('companies')->pluck('id')->map(fn ($id) => (int) $id)->all();

            if (empty($companyIds)) {
                $companyIds = [null];
            }
        }

        foreach ($companyIds as $companyId) {
            foreach ($types as $type) {
                $query = DB::table('module_settings')
                    ->where('module_name', $this->moduleName)
                    ->where('type', $type);

                if (in_array('company_id', $settingColumns, true)) {
                    $companyId === null ? $query->whereNull('company_id') : $query->where('company_id', $companyId);
                }

                if (!$query->exists()) {
                    $payload = [
                        'module_name' => $this->moduleName,
                        'status' => 'active',
                        'type' => $type,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (in_array('company_id', $settingColumns, true)) {
                        $payload['company_id'] = $companyId;
                    }

                    if (in_array('is_allowed', $settingColumns, true)) {
                        $payload['is_allowed'] = 1;
                    }

                    DB::table('module_settings')->insert($payload);
                }
            }
        }
    }

    private function syncPackages(): void
    {
        if (!Schema::hasTable('packages')) {
            return;
        }

        $packageColumns = Schema::getColumnListing('packages');

        if (!in_array('module_in_package', $packageColumns, true)) {
            return;
        }

        DB::table('packages')->orderBy('id')->chunkById(50, function ($packages) {
            foreach ($packages as $package) {
                $modules = json_decode((string) $package->module_in_package, true);

                if (!is_array($modules)) {
                    $modules = [];
                }

                if (!in_array($this->moduleName, $modules, true)) {
                    $modules[] = $this->moduleName;

                    DB::table('packages')->where('id', $package->id)->update([
                        'module_in_package' => json_encode(array_values(array_unique($modules))),
                    ]);
                }
            }
        });
    }

    private function addColumnIfMissing(string $table, string $column, callable $callback): void
    {
        if (!Schema::hasColumn($table, $column)) {
            Schema::table($table, $callback);
        }
    }
};
