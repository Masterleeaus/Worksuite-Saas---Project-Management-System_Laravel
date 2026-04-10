<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Models\{Transfer, StockLevel, Movement, Item, Warehouse};

class TransferController extends Controller
{
    public function index(){
        $transfers = Transfer::with(['item','from','to'])->latest()->paginate(20);
        return view('inventory::transfers.index', compact('transfers'));
    }

    public function create(){
        $items = Item::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        return view('inventory::transfers.create', compact('items','warehouses'));
    }

    public function store(Request $r){
        $data = $r->validate([
            'item_id'=>'required|exists:inventory_items,id',
            'from_warehouse_id'=>'required|exists:warehouses,id|different:to_warehouse_id',
            'to_warehouse_id'=>'required|exists:warehouses,id',
            'quantity'=>'required|numeric|min:0.0001',
            'note'=>'nullable|string'
        ]);
        $t = Transfer::create($data + ['status'=>'draft']);
        return redirect()->route('inventory.transfers.index')->with('status','Transfer created');
    }

    public function approve(Transfer $transfer){
        if ($transfer->status === 'approved') return back()->with('status','Already approved');
        DB::transaction(function() use ($transfer){
            // Decrement from source
            $from = StockLevel::firstOrCreate(['item_id'=>$transfer->item_id,'warehouse_id'=>$transfer->from_warehouse_id], ['on_hand'=>0,'min_qty'=>0,'max_qty'=>0]);
            $from->on_hand = max(0, $from->on_hand - $transfer->quantity);
            $from->save();
            Movement::create(['item_id'=>$transfer->item_id,'warehouse_id'=>$transfer->from_warehouse_id,'quantity'=>$transfer->quantity,'type'=>'out','note'=>'Transfer out']);

            // Increment to dest
            $to = StockLevel::firstOrCreate(['item_id'=>$transfer->item_id,'warehouse_id'=>$transfer->to_warehouse_id], ['on_hand'=>0,'min_qty'=>0,'max_qty'=>0]);
            $to->on_hand = $to->on_hand + $transfer->quantity;
            $to->save();
            Movement::create(['item_id'=>$transfer->item_id,'warehouse_id'=>$transfer->to_warehouse_id,'quantity'=>$transfer->quantity,'type'=>'in','note'=>'Transfer in']);

            $transfer->status = 'approved';
            $transfer->save();
        });
        return back()->with('status','Transfer approved');
    }

    public function destroy(Transfer $transfer){
        $transfer->delete();
        return back()->with('status','Transfer deleted');
    }
}
