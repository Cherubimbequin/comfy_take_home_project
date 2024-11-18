<?php

namespace App\Http\Controllers;

use App\DataTables\AdminPolicyManagerDataTable;
use Illuminate\Http\Request;

class AdminPolicyManagerController extends Controller
{
    public function index(AdminPolicyManagerDataTable $dataTable){
        return $dataTable->render('admin.pages.policyManager.index');
    }
}
