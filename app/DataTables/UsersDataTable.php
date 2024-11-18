<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Cache;


class UsersDataTable extends DataTable
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
        ->addColumn('role', function ($row) {
            switch ($row->role) {
                case 0:
                    return 'Admin';
                case 1:
                    return 'Customer';
                case 2:
                    return 'Agent';
                default:
                    return 'Unknown Role';
            }
        })
        ->addColumn('last_seen', function ($user) {
            $lastSeen = $user->last_seen ? Carbon::parse($user->last_seen)->diffForHumans() : 'N/A';
            $status = Cache::has('user-is-online-' . $user->id) ? '<span class="text-success">Online</span>' : '<span class="text-secondary">Offline</span>';

            return $status . ' | Last seen: ' . $lastSeen;
        })
        ->addColumn('action', function ($query) {
            $deleteUrl = route('admin.users.destroy', $query->id);
        
            return '
            <button type="button" class="btn btn-danger mb-2" onclick="confirmBlock(' . $query->id . ')">
                <i class="fas fa-trash"></i> Block
            </button>
        
            <form id="delete-form-' . $query->id . '" action="' . $deleteUrl . '" method="POST" style="display:none;">
                ' . csrf_field() . '
                <input type="hidden" name="_method" value="DELETE">
            </form>
            ';
        })
        
            ->rawColumns(['action',  'last_seen'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
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
            Column::make('email'),
            Column::make('role')->title('User Type'),
            Column::make('last_seen'),
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
        return 'Users_' . date('YmdHis');
    }
}
