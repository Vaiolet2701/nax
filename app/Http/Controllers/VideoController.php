<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        // Получаем все видео
        $videos = Video::all();

        // Возвращаем представление с данными
        return view('videos.index', compact('videos'));
    }
}