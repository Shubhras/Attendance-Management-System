<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $search = $request->get('search');

        $machines = Machine::when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('machines.index', compact('machines'));
    }

    public function create()
    {
        return view('machines.create'); // optional separate create view
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'image' => 'nullable|image|max:2048',
    //     ]);

    //     $data = $request->only(['name', 'description']);

    //     if ($request->hasFile('image')) {
    //         $data['image'] = $request->file('image')->store('machines', 'public');
    //     }

    //     $data['created_by'] = Auth::id();

    //     Machine::create($data);

    //     return redirect()->route('machines.index')->with('success', 'Machine created successfully.');
    // }
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048',
        'manager_names.*' => 'nullable|string|max:255',
    ]);

    $data = $request->only(['name', 'description']);

    // ✅ Store image directly in public/machines/photos
    if ($request->hasFile('image')) {
        $photoPath = public_path('machines_image/photos');
        if (!file_exists($photoPath)) {
            mkdir($photoPath, 0777, true);
        }
        $photo = $request->file('image');
        $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        $photo->move($photoPath, $photoName);
        $data['image'] = 'machines_image/photos/' . $photoName;
    }

    // ✅ Store manager names as JSON
    //$data['manager_names'] = json_encode(array_filter($request->manager_names ?? []));
    $data['manager_names'] = array_filter($request->manager_names ?? []);
    $data['created_by'] = Auth::id();

    Machine::create($data);

    return redirect()->route('machines.index')->with('success', 'Machine created successfully.');
}

    public function show(Machine $machine)
    {
        return view('machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('machines.edit', compact('machine'));
    }

    // public function update(Request $request, Machine $machine)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'image' => 'nullable|image|max:2048',
    //     ]);

    //     $data = $request->only(['name', 'description']);

    //     if ($request->hasFile('image')) {
    //         // remove old
    //         if ($machine->image) {
    //             Storage::disk('public')->delete($machine->image);
    //         }
    //         $data['image'] = $request->file('image')->store('machines', 'public');
    //     }

    //     $machine->update($data);

    //     return redirect()->route('machines.index')->with('success', 'Machine updated successfully.');
    // }
public function update(Request $request, Machine $machine)
{
    // $request->validate([
    //     'name' => 'required|string|max:255',
    //     'description' => 'required|string',
    //     'image' => 'nullable|image|max:2048',
    //     'manager_names.*' => 'nullable|string|max:255',
    // ]);

    // $data = $request->only(['name', 'description']);

    // if ($request->hasFile('image')) {
    //     // delete old file if exists
    //     if ($machine->image && file_exists(public_path($machine->image))) {
    //         unlink(public_path($machine->image));
    //     }
    //     $photoPath = public_path('machines_image/photos');
    //     if (!file_exists($photoPath)) {
    //         mkdir($photoPath, 0777, true);
    //     }
    //     $photo = $request->file('image');
    //     $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
    //     $photo->move($photoPath, $photoName);
    //     $data['image'] = 'machines_image/photos/' . $photoName;
    // }

    // $data['manager_names'] = json_encode(array_filter($request->manager_names ?? []));

    // $machine->update($data);

    // return redirect()->route('machines.index')->with('success', 'Machine updated successfully.');
    $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'required|string',
    'image' => 'nullable|image|max:2048',
    'manager_names.*' => 'nullable|string|max:255',
]);

$data = $request->only(['name', 'description']);

if ($request->hasFile('image')) {
    if ($machine->image && file_exists(public_path($machine->image))) {
        unlink(public_path($machine->image));
    }

    $photoPath = public_path('machines_image/photos');
    if (!file_exists($photoPath)) {
        mkdir($photoPath, 0777, true);
    }

    $photo = $request->file('image');
    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
    $photo->move($photoPath, $photoName);
    $data['image'] = 'machines_image/photos/' . $photoName;
}

// ✅ Save manager names as JSON
//$data['manager_names'] = json_encode(array_filter($request->manager_names ?? []));
$data['manager_names'] = array_filter($request->manager_names ?? []);

$machine->update($data);

return redirect()->route('machines.index')->with('success', 'Machine updated successfully.');

}

    public function destroy(Machine $machine)
    {
        // Soft delete - deleted_by set in model deleting event
        // Do not delete image on soft delete. If you want to delete file on forceDelete, handle that.
        $machine->delete();

        return redirect()->route('machines.index')->with('success', 'Machine deleted successfully.');
    }
    
}
