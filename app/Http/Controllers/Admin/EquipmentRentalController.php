<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentRental;

class EquipmentRentalController extends Controller
{
    public function index()
    {
        $rentals = EquipmentRental::with('user', 'equipment')->get();
        return view('admin.rentals.index', compact('rentals'));
    }

    public function approve(EquipmentRental $rental)
    {
        $rental->update(['is_approved' => true]);
        return back()->with('success', 'Аренда одобрена');
    }
}
