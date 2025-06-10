<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();
        return view('admin.equipment.index', compact('equipments'));
    }

    public function create() {
        return view('admin.equipment.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'city' => 'required',
            'image_path' => 'nullable|image',
        ]);

        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('equipment', 'public');
        }

        Equipment::create($data);
        return redirect()->route('admin.equipment.index');
    }
}
