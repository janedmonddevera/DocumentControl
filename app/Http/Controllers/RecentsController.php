<?php

namespace App\Http\Controllers;

use App\Models\Masterlist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecentsController extends Controller
{
    public function index() {

        $getMasterlistRecents = Masterlist::orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
         return Inertia::render('recents/index',
           ['data' => $getMasterlistRecents]);
    }
}
