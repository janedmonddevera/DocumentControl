<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Masterlist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecentsController extends Controller
{
    public function index()
    {

        $getMasterlistRecents = Masterlist::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $getColor = Color::latest()->first();
        // dd($getColor);
        return Inertia::render(
            'recents/index',
            [
                'data' => $getMasterlistRecents,
                'color' => $getColor
            ]
        );
    }
}
