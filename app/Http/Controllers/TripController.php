<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User; // Добавьте этот импорт
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('advanced')->except(['index', 'show']);
    }

    public function index()
    {
        $trips = Trip::with('user')->latest()->paginate(10);
        return view('trips.index', compact('trips'));
    }

    public function create()
    {
        return view('trips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1'
        ]);

        $trip = Auth::user()->trips()->create($validated);

        return redirect()->route('trips.show', $trip)->with('success', 'Поход успешно создан!');
    }

    public function show(Trip $trip)
    {
        return view('trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $this->authorize('update', $trip);
        return view('trips.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1'
        ]);

        $trip->update($validated);

        return redirect()->route('trips.show', $trip)->with('success', 'Поход успешно обновлен!');
    }

    public function destroy(Trip $trip)
    {
        $this->authorize('delete', $trip);
        $trip->delete();
        return redirect()->route('trips.index')->with('success', 'Поход успешно удален!');
    }

    public function join(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:12|max:100',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);
    
        $userId = Auth::id(); // Получаем ID текущего пользователя
    
        // Проверки
        if ($userId === $trip->user_id) {
            return back()->with('error', 'Вы не можете присоединиться к своему походу');
        }
    
        if ($trip->participants()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Вы уже участвуете в этом походе');
        }
    
        if ($trip->participants()->count() >= $trip->max_participants) {
            return back()->with('error', 'Мест больше нет');
        }
    
        // Сохранение в БД
        $trip->participants()->attach($userId, [
            'name' => $validated['name'],
            'age' => $validated['age'],
            'phone' => $validated['phone'],
            'notes' => $validated['notes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return back()->with('success', 'Вы успешно присоединились!');
    }
    public function approve(Trip $trip)
{
    $trip->update(['status' => 'approved']);
    return back()->with('success', 'Поход успешно одобрен');
}

public function reject(Trip $trip)
{
    $trip->update(['status' => 'rejected']);
    return back()->with('success', 'Поход отклонен');
}
}