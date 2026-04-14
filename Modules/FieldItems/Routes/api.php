<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\FieldItems\Entities\Item;
use Modules\FieldItems\Entities\TaskItem;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Mobile API routes for FieldItems module.
| Supports barcode scanning → item lookup → job consumption logging.
|
*/

Route::middleware('auth:api')->prefix('v1')->group(function () {

    // Item catalogue (mobile catalogue browse)
    Route::get('/items', function (Request $request) {
        $items = Item::select('id', 'name', 'price', 'description', 'category_id', 'sub_category_id')
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate(50);

        return response()->json($items);
    });

    // Barcode / QR code scan lookup — cleaner scans item on van
    Route::get('/items/barcode/{barcode}', function ($barcode) {
        $item = Item::where('barcode', $barcode)->orWhere('sku', $barcode)->firstOrFail();

        return response()->json(['item' => $item]);
    });

    // Log item consumption on a task (van scan-out)
    Route::post('/task-items', function (Request $request) {
        $request->validate([
            'task_id'    => 'required|integer',
            'item_id'    => 'required|integer|exists:items,id',
            'quantity'   => 'required|numeric|min:0.01',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $item = Item::findOrFail($request->item_id);

        $taskItem = TaskItem::create([
            'task_id'    => $request->task_id,
            'item_id'    => $request->item_id,
            'quantity'   => $request->quantity,
            'unit_price' => $request->unit_price ?? $item->price,
            'company_id' => $request->user()->company_id ?? null,
        ]);

        return response()->json(['status' => 'ok', 'task_item' => $taskItem], 201);
    });

    // List items consumed on a task
    Route::get('/tasks/{taskId}/items', function ($taskId) {
        $taskItems = TaskItem::with('item')
            ->where('task_id', $taskId)
            ->get();

        return response()->json(['task_items' => $taskItems]);
    });
});
