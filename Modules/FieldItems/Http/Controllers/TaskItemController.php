<?php

namespace Modules\FieldItems\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use App\Helper\Reply;
use Modules\FieldItems\Entities\Item;
use Modules\FieldItems\Entities\TaskItem;

class TaskItemController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'fielditems::app.menu.jobConsumption';
    }

    /**
     * Display items consumed on a given task.
     *
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function index($taskId)
    {
        abort_403(user()->permission('view_item') === 'none');

        $this->taskId = $taskId;
        $this->taskItems = TaskItem::with('item')
            ->where('task_id', $taskId)
            ->get();

        if (request()->ajax()) {
            return Reply::dataOnly([
                'status'     => 'success',
                'task_items' => $this->taskItems,
            ]);
        }

        return view('fielditems::task_items.index', $this->data);
    }

    /**
     * Log an item as consumed on a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        abort_403(!in_array(user()->permission('add_item'), ['all', 'added']));

        $request->validate([
            'task_id'    => 'required|integer',
            'item_id'    => 'required|integer',
            'quantity'   => 'required|numeric|min:0.01',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $item = Item::findOrFail($request->item_id);

        $taskItem = TaskItem::create([
            'task_id'    => $request->task_id,
            'item_id'    => $request->item_id,
            'quantity'   => $request->quantity,
            'unit_price' => $request->unit_price ?? $item->price,
            'company_id' => company()->id,
        ]);

        return Reply::successWithData(__('messages.recordSaved'), ['task_item' => $taskItem]);
    }

    /**
     * Update an existing task-item consumption record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        abort_403(!in_array(user()->permission('edit_item'), ['all', 'added']));

        $request->validate([
            'quantity'   => 'sometimes|required|numeric|min:0.01',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $taskItem = TaskItem::findOrFail($id);
        $taskItem->fill($request->only(['quantity', 'unit_price']));
        $taskItem->save();

        return Reply::successWithData(__('messages.updateSuccess'), ['task_item' => $taskItem]);
    }

    /**
     * Remove a task-item consumption record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        abort_403(user()->permission('delete_item') !== 'all');

        TaskItem::findOrFail($id)->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
