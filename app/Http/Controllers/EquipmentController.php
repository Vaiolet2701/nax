<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Equipment;
use App\Models\EquipmentRental;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::all();  // Получаем все снаряжения
        return view('equipments.index', compact('equipment')); // Передаём в шаблон
    }

    public function showRentForm(Equipment $equipment)
    {
        return view('equipments.rent', compact('equipment'));
    }

    public function submitRental(Request $request, Equipment $equipment)
    {
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        EquipmentRental::create([
            'user_id' => auth()->id(),
            'equipment_id' => $equipment->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('equipments.index')->with('success', 'Заявка на аренду отправлена');
    }
}

