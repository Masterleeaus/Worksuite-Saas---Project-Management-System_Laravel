<?php

namespace Modules\CustomerFeedback\DataTables;

use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Column;
use Modules\CustomerFeedback\Entities\FeedbackTicket;

class FeedbackTicketDataTable extends BaseDataTable
{
    private string $viewPermission;
    private string $editPermission;
    private string $deletePermission;

    public function __construct()
    {
        parent::__construct();
        $this->viewPermission   = user()->permission('view_feedback');
        $this->editPermission   = user()->permission('manage_surveys');
        $this->deletePermission = user()->permission('manage_surveys');
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('title', function (FeedbackTicket $row) {
                return '<a href="' . route('customer-feedback.tickets.show', $row->id) . '">' . e($row->title) . '</a>';
            })
            ->editColumn('feedback_type', function (FeedbackTicket $row) {
                return ucfirst(str_replace('_', ' ', $row->feedback_type));
            })
            ->editColumn('status', function (FeedbackTicket $row) {
                $badge = match ($row->status) {
                    'open'        => 'badge-warning',
                    'in_progress' => 'badge-info',
                    'resolved'    => 'badge-success',
                    'closed'      => 'badge-secondary',
                    default       => 'badge-light',
                };
                return '<span class="badge ' . $badge . '">' . ucfirst(str_replace('_', ' ', $row->status)) . '</span>';
            })
            ->editColumn('priority', function (FeedbackTicket $row) {
                $badge = match ($row->priority) {
                    'critical' => 'badge-danger',
                    'high'     => 'badge-warning',
                    'medium'   => 'badge-info',
                    default    => 'badge-secondary',
                };
                return '<span class="badge ' . $badge . '">' . ucfirst($row->priority) . '</span>';
            })
            ->editColumn('created_at', function (FeedbackTicket $row) {
                return $row->created_at?->format('d M Y H:i') ?? '--';
            })
            ->addColumn('action', function (FeedbackTicket $row) {
                $html = '<div class="task_view"><div class="dropdown">'
                    . '<a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" '
                    . 'type="link" id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" '
                    . 'aria-haspopup="true" aria-expanded="false"><i class="icon-options-vertical icons"></i></a>'
                    . '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '">';

                $html .= '<a href="' . route('customer-feedback.tickets.show', $row->id) . '" class="dropdown-item">'
                    . '<i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

                if ($this->deletePermission === 'all') {
                    $html .= '<a class="dropdown-item delete-table-row" data-user-id="' . $row->id . '" '
                        . 'href="javascript:;">'
                        . '<i class="fa fa-trash mr-2"></i>' . __('app.delete') . '</a>';
                }

                $html .= '</div></div></div>';

                return $html;
            })
            ->rawColumns(['title', 'status', 'priority', 'action'])
            ->removeColumn('password');
    }

    public function query(FeedbackTicket $model)
    {
        $request = $this->request();

        $query = FeedbackTicket::with(['requester', 'agent'])
            ->select('feedback_tickets.*');

        if ($request->has('feedback_type') && $request->feedback_type !== 'all') {
            $query->where('feedback_type', $request->feedback_type);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('feedback-ticket-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->parameters([
                'initComplete' => 'function () { window.LaravelDataTables["feedback-ticket-table"].buttons().container().appendTo("#table-actions"); }',
            ])
            ->language(__('app.datatable'));
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('app.id')),
            Column::make('title')->title(__('customer-feedback::app.title')),
            Column::make('feedback_type')->title(__('customer-feedback::app.type')),
            Column::make('status')->title(__('app.status')),
            Column::make('priority')->title(__('customer-feedback::app.priority')),
            Column::make('created_at')->title(__('app.createdAt')),
            Column::computed('action')->title(__('app.action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-right pr-20'),
        ];
    }

    protected function filename(): string
    {
        return 'FeedbackTickets_' . now()->format('YmdHis');
    }
}
