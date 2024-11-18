<?php

namespace App\DataTables;

use App\Models\Payment;
use App\Models\Payments;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PaymentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->editColumn('created_at', function ($row) {
            return Carbon::parse($row->created_at)
                ->locale('en')
                ->isoFormat('MMMM Do YYYY');
        })
        ->addColumn('user_id', function ($row) {
            return $row->user
                ? $row->user->name
                : 'No User Found';
        })
        ->addColumn('user_id', function ($row) {
            return $row->user
                ? $row->user->name
                : 'No User Found';
        })
        ->addColumn('policy_id', function ($row) {
            return $row->policy
                ? $row->policy->policy_number
                : 'No policy Found';
        })
            ->addColumn('action', 'payment.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Payments $model): QueryBuilder
    {
        // return $model->newQuery();
        return $model->newQuery()->where('user_id', auth()->id());

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('payment-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        // Button::make('print'),
                        // Button::make('reset'),
                        // Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('user_id')->title('Name'),
            Column::make('policy_id')->title('Policy Number'),
            Column::make('reference'),
            Column::make('amount'),
            Column::make('status'),
            Column::make('created_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Payment_' . date('YmdHis');
    }
}
