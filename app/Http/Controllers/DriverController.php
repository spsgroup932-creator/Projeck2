<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::orderBy('id', 'desc')->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function show(Driver $driver)
    {
        return redirect()->route('drivers.edit', $driver->id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'age' => 'required|numeric',
            'ktp_photo' => 'nullable|image|max:2048'
        ]);

        $last = Driver::latest('id')->first();
        $number = $last ? (int)substr($last->driver_code, 4) + 1 : 1;
        $code = 'DRV-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['driver_code'] = $code;

        if ($request->hasFile('ktp_photo')) {
            $data['ktp_photo'] = $request->file('ktp_photo')->store('ktp_drivers', 'public');
        }

        Driver::create($data);
        return redirect()->route('drivers.index')->with('success', 'Driver berhasil ditambahkan kawan!');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'age' => 'required|numeric',
            'ktp_photo' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('ktp_photo')) {
            if ($driver->ktp_photo) {
                Storage::disk('public')->delete($driver->ktp_photo);
            }
            $data['ktp_photo'] = $request->file('ktp_photo')->store('ktp_drivers', 'public');
        }

        $driver->update($data);
        return redirect()->route('drivers.index')->with('success', 'Driver berhasil diupdate kawan!');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->ktp_photo) {
            Storage::disk('public')->delete($driver->ktp_photo);
        }
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver berhasil dihapus kawan!');
    }
}
