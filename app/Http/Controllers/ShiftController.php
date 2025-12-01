<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::latest()->paginate(10);
        return view('shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'clock_in_time' => 'required',
            'clock_out_time' => 'required',
        ]);

        Shift::create([
            'shift_name' => $request->shift_name,
            'clock_in_time' => $request->clock_in_time,
            'clock_out_time' => $request->clock_out_time,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift created successfully');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'clock_in_time' => 'required',
            'clock_out_time' => 'required',
        ]);

        $shift->update($request->only('shift_name', 'clock_in_time', 'clock_out_time'));

        return redirect()->route('shifts.index')->with('success', 'Shift updated successfully');
    }
    public function show(Shift $shift)
    {
        return view('shifts.show', compact('shift'));
    }
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift deleted successfully');
    }
}
