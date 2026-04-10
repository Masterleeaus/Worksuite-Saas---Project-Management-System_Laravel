<?php

namespace App\Services\Admin\Acl\Services;

use App\Models\Acl\Module;

class ModuleService
{
    public function getAllModules($search = null)
    {
        $query = Module::with('category');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('module_name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%")
                  ->orWhere('route', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        return $query->orderBy('display_order', 'asc')->get();
    }

    public function createModule(array $data)
    {
        return Module::create($data);
    }

    public function updateModule(Module $module, array $data)
    {
        $module->update($data);
        return $module;
    }

    public function deleteModule(Module $module)
    {
        return $module->delete();
    }
}
