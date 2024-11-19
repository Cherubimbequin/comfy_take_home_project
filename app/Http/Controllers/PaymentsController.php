<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;
use App\DataTables\PaymentDataTable;
use App\DataTables\AdminPaymentDataTable;

class PaymentsController extends Controller
{

    public function index(AdminPaymentDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.Payments.index');
    }


    public function index_users(PaymentDataTable $dataTable)
    {
        return $dataTable->render('users.pages.Payments.index');
    }


    public function index_agent(PaymentDataTable $dataTable)
    {
        return $dataTable->render('agent.pages.Payments.index');
    }


}
