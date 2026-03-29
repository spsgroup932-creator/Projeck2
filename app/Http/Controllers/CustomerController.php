<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:customers',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Generate Customer Code (e.g., CUST-0001)
        $latestCustomer = Customer::withoutGlobalScopes()->orderBy('id', 'desc')->first();
        if (!$latestCustomer) {
            $customerCode = 'CUST-0001';
        } else {
            // Extract the number part from CUST-XXXX and increment
            $number = intval(substr($latestCustomer->customer_code, 5)) + 1;
            $customerCode = 'CUST-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }
        $validated['customer_code'] = $customerCode;

        // Handle File Upload for KTP
        if ($request->hasFile('ktp_photo')) {
            $path = $request->file('ktp_photo')->store('ktp_photos', 'public');
            $validated['ktp_photo'] = $path;
        }

        Customer::create($validated);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Customer berhasil ditambahkan kawan!');
        }

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan kawan!');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:customers,nik,' . $customer->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle File Upload for KTP
        if ($request->hasFile('ktp_photo')) {
            // Delete old KTP photo from storage if it exists
            if ($customer->ktp_photo && Storage::disk('public')->exists($customer->ktp_photo)) {
                Storage::disk('public')->delete($customer->ktp_photo);
            }
            $path = $request->file('ktp_photo')->store('ktp_photos', 'public');
            $validated['ktp_photo'] = $path;
        }

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        // Delete KTP photo from storage before deleting customer
        if ($customer->ktp_photo && Storage::disk('public')->exists($customer->ktp_photo)) {
            Storage::disk('public')->delete($customer->ktp_photo);
        }
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
