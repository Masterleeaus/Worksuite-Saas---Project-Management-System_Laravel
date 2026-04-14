<?php

namespace Modules\ServiceManagement\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\ServiceManagement\Entities\Service;

class CmsSurfaceController extends Controller
{
    public function snapshot(): JsonResponse
    {
        $count = Service::query()->count();

        return response()->json([
            'module' => 'servicemanagement',
            'surfaces' => [
                'services_catalog' => [
                    'route_name' => 'customer.service.index',
                    'endpoint' => route('customer.service.index'),
                    'service_count' => $count,
                    'render_points' => [
                        'api' => 'Modules\\ServiceManagement\\Http\\Controllers\\Api\\V1\\Customer\\ServiceController@index',
                    ],
                ],
                'company_services_admin' => [
                    'route_name' => 'services.index',
                    'endpoint' => route('services.index'),
                    'render_points' => [
                        'sidebar' => 'servicemanagement::sections.sidebar',
                        'view' => 'servicemanagement::cleaning.index',
                    ],
                ],
            ],
        ]);
    }
}
