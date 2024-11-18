<?php

namespace App\DataTables;

use App\Models\PolicyType;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PolicyTypeDataTable extends DataTable
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
            ->addColumn('action', function ($query) {
                $editUrl = route('admin.policy.type.edit', $query->id);
            
                return '
                    <a href="' . $editUrl . '" class="btn btn-primary mb-2">
                        <i data-feather="edit"></i> Edit
                    </a>
                    <button onclick="handleDelete(' . $query->id . ')" class="btn btn-danger mb-2">
                        <i data-feather="trash-2"></i> Delete
                    </button>
                ';
            })
                                           
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PolicyType $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('policytype-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
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
            Column::make('name'),
            Column::make('price'),
            Column::make('description'),
            Column::make('user_id')->title('Created By'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PolicyType_' . date('YmdHis');
    }
}
