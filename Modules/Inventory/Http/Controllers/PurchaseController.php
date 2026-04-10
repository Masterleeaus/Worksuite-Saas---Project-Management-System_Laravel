<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\{PurchaseOrder, PurchaseOrderItem, GoodsReceipt, GoodsReceiptItem, Supplier, Item, StockLevel, Movement};

class PurchaseController extends Controller
{
    public function index() {
        $orders = PurchaseOrder::with('supplier')->latest()->paginate(20);
        return view('inventory::purchasing.index', compact('orders'));
    }

    public function create() {
        $suppliers = Supplier::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        return view('inventory::purchasing.create', compact('suppliers','items'));
    }

    public function store(Request $r) {
        $data = $r->validate([
            'supplier_id'=>'required|exists:suppliers,id',
            'reference'=>'nullable|string|max:190',
            'currency'=>'nullable|string|max:3',
            'notes'=>'nullable|string',
            'items'=>'required|array|min:1',
            'items.*.item_id'=>'required|exists:inventory_items,id',
            'items.*.qty_ordered'=>'required|numeric|min:0.0001',
            'items.*.unit_cost'=>'required|numeric|min:0',
        ]);
        $po = PurchaseOrder::create([
            'supplier_id'=>$data['supplier_id'],
            'status'=>'ordered',
            'ordered_at'=>now(),
            'reference'=>$data['reference'] ?? null,
            'currency'=>$data['currency'] ?? 'AUD',
            'notes'=>$data['notes'] ?? null,
            'total'=>0,
        ]);
        $total = 0;
        foreach ($data['items'] as $row){
            $total += ($row['qty_ordered'] * $row['unit_cost']);
            PurchaseOrderItem::create([
                'purchase_order_id'=>$po->id,
                'item_id'=>$row['item_id'],
                'qty_ordered'=>$row['qty_ordered'],
                'unit_cost'=>$row['unit_cost'],
            ]);
        }
        $po->update(['total'=>$total]);
        return redirect()->route('inventory.purchasing.index')->with('status','PO created');
    }

    public function receiveForm(PurchaseOrder $order) {
        $order->load('items.item');
        return view('inventory::purchasing.receive', compact('order'));
    }

    // Simple GRN + weighted-average costing update
    public function receive(Request $r, PurchaseOrder $order) {
        $data = $r->validate([
            'warehouse_id'=>'required|exists:warehouses,id',
            'items'=>'required|array|min:1',
            'items.*.po_item_id'=>'required|exists:purchase_order_items,id',
            'items.*.qty_received'=>'required|numeric|min:0.0001',
            'items.*.unit_cost'=>'required|numeric|min:0',
        ]);

        DB::transaction(function() use ($order, $data) {
            $grn = GoodsReceipt::create([
                'purchase_order_id'=>$order->id,
                'warehouse_id'=>$data['warehouse_id'],
                'received_at'=>now(),
            ]);

            foreach ($data['items'] as $row) {
                $poi = PurchaseOrderItem::findOrFail($row['po_item_id']);
                $itemId = $poi->item_id;

                GoodsReceiptItem::create([
                    'goods_receipt_id'=>$grn->id,
                    'purchase_order_item_id'=>$poi->id,
                    'item_id'=>$itemId,
                    'qty_received'=>$row['qty_received'],
                    'unit_cost'=>$row['unit_cost'],
                ]);

                // Update StockLevel (on_hand) and create Movement IN
                $sl = StockLevel::firstOrCreate(['item_id'=>$itemId,'warehouse_id'=>$data['warehouse_id']], ['on_hand'=>0,'min_qty'=>0,'max_qty'=>0]);
                $oldQty = $sl->on_hand;
                $newQty = $oldQty + $row['qty_received'];
                $sl->on_hand = $newQty;
                $sl->save();

                Movement::create([
                    'item_id'=>$itemId,
                    'warehouse_id'=>$data['warehouse_id'],
                    'quantity'=>$row['qty_received'],
                    'type'=>'in',
                    'note'=>'GRN auto',
                ]);
            }

            // Update order status (simplistic)
            $order->status = 'received';
            $order->save();
        });

        return redirect()->route('inventory.purchasing.index')->with('status','PO received');
    }
}
