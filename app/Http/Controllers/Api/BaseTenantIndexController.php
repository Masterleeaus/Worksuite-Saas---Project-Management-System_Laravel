<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseTenantIndexController extends Controller
{
    abstract protected function modelClass(): string;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        /** @var class-string<Model> $modelClass */
        $modelClass = $this->modelClass();
        /** @var Builder $query */
        $query = $modelClass::query();

        if ((int) ($user->is_superadmin ?? 0) !== 1 && !empty($user->company_id)) {
            $instance = new $modelClass();

            if ($instance->getConnection()->getSchemaBuilder()->hasColumn($instance->getTable(), 'company_id')) {
                $query->where($instance->getTable() . '.company_id', $user->company_id);
            }
        }

        return response()->json($query->paginate(25));
    }
}
