<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function create()
    {
        return view('admin.course-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name',
        ]);

        CourseCategory::create($request->only('name'));

        return redirect()->route('admin.courses.create')->with('success', 'Категория успешно создана.');
    }
    public function show($id)
    {
        // Находим категорию по ID
        $category = CourseCategory::findOrFail($id);

        // Получаем курсы, связанные с этой категорией
        $courses = $category->courses;

        // Передаем данные в представление
        return view('course-categories.show', compact('category', 'courses'));
    }
}