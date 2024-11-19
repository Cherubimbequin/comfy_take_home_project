<?php

namespace App\Http\Controllers;

use App\Models\PolicyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\DataTables\PolicyTypeDataTable;

class PolicyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PolicyTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.policyType.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.policyType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:policy_types,name|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            PolicyType::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.policy.type')->with('success', 'Policy Type created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating policy type: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the Policy Type. Please try again.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(PolicyType $policyType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $policyType = PolicyType::findOrFail($id);
        return view('admin.pages.policyType.edit', compact('policyType'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:policy_types,name,' . $id . '|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $policyType = PolicyType::findOrFail($id);
            $policyType->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.policy.type')->with('success', 'Policy Type updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating policy type (ID: ' . $id . '): ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the Policy Type. Please try again.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $policyType = PolicyType::findOrFail($id);
        $policyType->delete();
        return response()->json(['message' => 'Policy Type deleted successfully.']);
    }
}
