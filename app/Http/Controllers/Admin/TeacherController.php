<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // Метод для отображения списка преподавателей
    public function index()
    {
        // Фильтруем по роли 'teacher'
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Метод для создания нового преподавателя
    public function create()
    {
        return view('admin.teachers.create');
    }

    // Метод для сохранения нового преподавателя
    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'age' => 'required|integer|min:18',
            'work_experience' => 'required|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        // Обработка аватара
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('public/avatars');
            $validated['avatar'] = str_replace('public/', '', $path);
        }

        // Создание нового преподавателя
        $teacher = User::create([
            'name' => $validated['name'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'avatar' => $validated['avatar'] ?? null,
            'age' => $validated['age'],
            'work_experience' => $validated['work_experience'],
            'bio' => $validated['bio'],
            'is_teacher' => true,
        ]);

        // Перенаправляем обратно с сообщением об успешном создании
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Преподаватель успешно создан');
    }
// Метод для удаления преподавателя
public function destroy(User $teacher)
{
    $teacher->delete();
    return redirect()->route('admin.teachers.index')
        ->with('success', 'Преподаватель успешно удален');
}
}
