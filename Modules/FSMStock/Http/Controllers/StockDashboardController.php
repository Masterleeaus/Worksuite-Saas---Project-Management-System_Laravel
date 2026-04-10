<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\FSMStock\Models\FSMStockCategory;
use Modules\FSMStock\Models\FSMStockItem;
use Modules\FSMStock\Models\FSMStockMove;

class StockDashboardController extends Controller
{
    public function index()
    {
        $lowStockItems   = FSMStockItem::whereRaw('current_qty < min_qty')->get();
        $recentMoves     = FSMStockMove::with(['product', 'order', 'mover'])
            ->latest('moved_at')
            ->take(20)
            ->get();
        $totalItems      = FSMStockItem::count();
        $totalCategories = FSMStockCategory::count();

        return view('fsmstock::dashboard.index', compact(
            'lowStockItems',
            'recentMoves',
            'totalItems',
            'totalCategories'
        ));
    }
}
