<?php

namespace App\Http\Controllers;

use App\Models\BillingType;
use Illuminate\Http\Request;

class BillingTypeController extends Controller
{
    public function index()
    {
        $billingtype = BillingType::paginate(10);
        return view('management.billingtype.index', compact('billingtype'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'billing_type' => 'required',
            'description' => 'required',
            'amount' => 'required'
        ]);

        BillingType::create([
            'code' => $request->code,
            'name' => $request->billing_type,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        return response()->json([
            'message' => 'Billing Type created successfully!',
            'redirect' => route('billingtype.index')
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'code' => 'required|string|max:50',
            'billing_type' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        // Cari data billing type berdasarkan ID
        $billingType = BillingType::findOrFail($id);

        // Update data
        $billingType->update([
            'code' => $request->code,
            'name' => $request->billing_type,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis tagihan berhasil diupdate.'
        ]);
    }

    public function destroy($id)
    {
        $billingType = BillingType::findOrFail($id);

        $billingType->delete();

        return response()->json([
            'message' => 'Jenis Pembiayaan berhasil dihapus'
        ]);
    }
}
