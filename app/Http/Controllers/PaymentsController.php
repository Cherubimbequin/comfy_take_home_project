<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;
use App\DataTables\PaymentDataTable;
use App\DataTables\AdminPaymentDataTable;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AdminPaymentDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.Payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function index_users(PaymentDataTable $dataTable)
    {
        return $dataTable->render('users.pages.Payments.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function index_agent(PaymentDataTable $dataTable)
    {
        return $dataTable->render('agent.pages.Payments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        //
    }
}
