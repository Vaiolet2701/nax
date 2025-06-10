<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentRental;
use Illuminate\Support\Facades\Auth;

class EquipmentRentalController extends Controller
{
    // Показывает форму аренды для пользователя
    public function create($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipments.rent', compact('equipment'));
    }

    // Обрабатывает форму аренды
    public function store(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        EquipmentRental::create([
            'user_id' => Auth::id(),
            'equipment_id' => $id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_approved' => false,
        ]);

        return redirect()->route('equipments.index')->with('success', 'Заявка на аренду отправлена!');
    }

}
