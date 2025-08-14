<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentsController extends Controller
{
    public function index() {

        $departments = Department::latest()->get();
         return Inertia::render('departments/index',
           ['data' => $departments]);
    }
}
