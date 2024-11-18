<?php

namespace App\DataTables;

use App\Models\PolicyManager;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PolicyManagerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('policy_type_id', function ($row) {
            return $row->policyType
                ? $row->policyType->name
                : 'No Project Found';
        })
        ->editColumn('created_at', function ($row) {
            return Carbon::parse($row->created_at)
                ->locale('en')
                ->isoFormat('MMMM Do YYYY');
        })
        ->editColumn('start_date', function ($row) {
            return Carbon::parse($row->start_date)
                ->locale('en')
                ->isoFormat('MMMM Do YYYY');
        })
        ->editColumn('end_date', function ($row) {
            return Carbon::parse($row->end_date)
                ->locale('en')
                ->isoFormat('MMMM Do YYYY');
        })
            ->addColumn('action', 'policymanager.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PolicyManager $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('policymanager-table')
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
            
            Column::make('policy_number'),
            Column::make('policy_type_id')->title('Policy Type'),
            Column::make('status'),
            Column::make('premium_amount'),
            Column::make('start_date'),
            Column::make('end_date'),
            Column::make('next_of_kin'),
            Column::make('created_at'),
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PolicyManager_' . date('YmdHis');
    }
}
