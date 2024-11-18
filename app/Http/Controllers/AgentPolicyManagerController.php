<?php

namespace App\Http\Controllers;

use App\DataTables\AgentPolicyManagerDataTable;
use Illuminate\Http\Request;

class AgentPolicyManagerController extends Controller
{
    public function index(AgentPolicyManagerDataTable $dataTable)
    {
        return $dataTable->render('agent.pages.policyManager.index');
    }
}
