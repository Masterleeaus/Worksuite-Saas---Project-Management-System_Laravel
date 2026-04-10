<?php

namespace App\Services\Admin\Acl\Services;

use App\Models\Acl\ModuleCategory;

class ModuleCategoryService
{
    public function getAllCategories()
    {
        return ModuleCategory::orderBy('display_order', 'asc')->get();
    }

    public function createCategory(array $data)
    {
        return ModuleCategory::create($data);
    }

    public function updateCategory(ModuleCategory $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function deleteCategory(ModuleCategory $category)
    {
        return $category->delete();
    }
}
