<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Contractor::query();
        
        // search by name, mobile, email, company_name
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        // echo"dd";die;

        // show only non-deleted (SoftDeletes takes care by default)
        $contractors = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('contractors.index', compact('contractors'));
    }

    public function create()
    {
        // We'll show the create modal in index; but still return a view if needed.
        return view('contractors.create');
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'mobile' => 'required|string|max:50|unique:contractors,mobile',
    //         'email' => 'nullable|email|max:255',
    //         'govid' => 'nullable|string|max:255',
    //         'dob' => 'nullable|date',
    //         'gender' => 'nullable|in:male,female,other',
    //         'address' => 'nullable|string',
    //         'company_name' => 'nullable|string|max:255',
    //         'self_photo' => 'nullable|image|max:2048',
    //     ]);

    //     if ($request->hasFile('self_photo')) {
    //         $path = $request->file('self_photo')->store('contractors', 'public');
    //         $data['self_photo'] = $path;
    //     }

    //     // uuid & created_by are handled in model boot
    //     $contractor = Contractor::create($data);

    //     return redirect()->route('contractors.index')
    //         ->with('success', 'Contractor created successfully.');
    // }
public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'mobile' => 'required|string|max:50|unique:contractors,mobile',
        'email' => 'nullable|email|max:255',
        'govid' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string',
        'company_name' => 'nullable|string|max:255',
        'self_photo' => 'nullable|image|max:2048',
    ]);

    // ✅ Create folder if missing
    $photoPath = public_path('contractors_image/photos');
    if (!file_exists($photoPath)) {
        mkdir($photoPath, 0777, true);
    }

    // ✅ Save photo directly to public/contractors/photos
    if ($request->hasFile('self_photo')) {
        $photo = $request->file('self_photo');
        $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        $photo->move($photoPath, $photoName);
        $data['self_photo'] = 'contractors_image/photos/' . $photoName;
    }

    // ✅ Assign unique employee code range
    $last = Contractor::orderBy('id', 'desc')->first();
    $lastEnd = $last ? $last->code_end : 999;
    $data['code_start'] = $lastEnd + 1;
    $data['code_end'] = $data['code_start'] + 999; // range of 1000 codes

    $contractor = Contractor::create($data);

    return redirect()->route('contractors.index')
        ->with('success', 'Contractor created successfully with code range '
            . $contractor->code_start . ' - ' . $contractor->code_end);
}


    public function show(Contractor $contractor)
    {
        return view('contractors.show', compact('contractor'));
    }

    public function edit(Contractor $contractor)
    {
        return view('contractors.edit', compact('contractor'));
    }

    // public function update(Request $request, Contractor $contractor)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'mobile' => 'required|string|max:50|unique:contractors,mobile,' . $contractor->id,
    //         'email' => 'nullable|email|max:255',
    //         'govid' => 'nullable|string|max:255',
    //         'dob' => 'nullable|date',
    //         'gender' => 'nullable|in:male,female,other',
    //         'address' => 'nullable|string',
    //         'company_name' => 'nullable|string|max:255',
    //         'self_photo' => 'nullable|image|max:2048',
    //     ]);

    //     if ($request->hasFile('self_photo')) {
    //         // delete old if exists
    //         if ($contractor->self_photo && Storage::disk('public')->exists($contractor->self_photo)) {
    //             Storage::disk('public')->delete($contractor->self_photo);
    //         }
    //         $path = $request->file('self_photo')->store('contractors', 'public');
    //         $data['self_photo'] = $path;
    //     }

    //     $contractor->update($data);

    //     return redirect()->route('contractors.index')
    //         ->with('success', 'Contractor updated successfully.');
    // }
public function update(Request $request, Contractor $contractor)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'mobile' => 'required|string|max:50|unique:contractors,mobile,' . $contractor->id,
        'email' => 'nullable|email|max:255',
        'govid' => 'nullable|string|max:255',
        'dob' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string',
        'company_name' => 'nullable|string|max:255',
        'self_photo' => 'nullable|image|max:2048',
    ]);

    // ✅ Ensure folder exists
    $photoPath = public_path('contractors_image/photos');
    if (!file_exists($photoPath)) {
        mkdir($photoPath, 0777, true);
    }

    // ✅ Replace old photo if new one uploaded
    if ($request->hasFile('self_photo')) {
        // Delete old photo if it exists
        if ($contractor->self_photo && file_exists(public_path($contractor->self_photo))) {
            unlink(public_path($contractor->self_photo));
        }

        $photo = $request->file('self_photo');
        $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        $photo->move($photoPath, $photoName);
        $data['self_photo'] = 'contractors_image/photos/' . $photoName;
    }

    $contractor->update($data);

    return redirect()->route('contractors.index')
        ->with('success', 'Contractor updated successfully.');
}

    public function destroy(Request $request, Contractor $contractor)
    {
        // Soft delete - deleted_by set in model boot deleting event
        $contractor->delete();

        return redirect()->route('contractors.index')
            ->with('success', 'Contractor deleted successfully.');
    }
}
