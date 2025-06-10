<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Promotion;
use App\Models\CourseCategory;
use App\Models\AdminArticle;
use App\Models\Video;
use App\Models\UserArticle;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Enrollment;
class HomeController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        $categories = CourseCategory::all();
        $userArticles = UserArticle::all();
        $reviews = Review::all();
        $courses = Course::inRandomOrder()->take(5)->get();
        $adminArticles = AdminArticle::inRandomOrder()->take(3)->get();
        $userArticles = UserArticle::inRandomOrder()->take(3)->get();
        $videos = Video::inRandomOrder()->take(1)->get(); // 1 видео

        return view('index', compact(
            'promotions',
            'categories',
            'courses', // Передаём переменную $courses
            'adminArticles',
            'videos',
            'userArticles',
            'reviews'
        ));
    }
}
