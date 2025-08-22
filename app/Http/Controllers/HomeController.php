<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // لو عندك Auth::routes() غالبًا في راوت /home:
    public function index()
    {
        // رجّع أي View عندك، أو رسالة بسيطة مؤقتًا
        return view('welcome'); // أو: return 'Home OK';
    }
}

