<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Documents;

use Illuminate\Http\Request;

use Inertia\Inertia;
class DocumentsController extends Controller
{
    public function index(Request $request)
    {


        $perPage = $request->input('per_page', 10);
        $status = $request->input('unit', null);
        $sortField = $request->input('sort_field', 'doc_name');
        $sortDirection = $request->input('sort_direction', 'desc');
         $sortName = $request->input('doc_name', null);
        $filters = [];
        if (!empty($status)) {
            $filters[] = [
                'id' => 'unit',
                'value' => $status
            ];
        }
        $doc_code = $request->input('doc_code', null);
        $departments = Department::select('unit', 'code')->get();
        $products = Documents::query()
        ->when($status, function ($query, $status) {
            if (is_array($status) && !empty($status)) {
                $query->whereIn('unit', $status);
            }
        })->when(
                $sortName,
                fn($q, $c) =>
                $q->where('doc_name', 'like', "%{$c}%")
            )
           ->when(
                $doc_code,
                fn($d, $f) =>
                $d->where('doc_code', 'like', "%{$f}%")
            )
        ->orderBy($sortField, $sortDirection)->paginate(perPage: $perPage);
        return Inertia::render('documents/index', [
            'data' => $products,
            'filter' => $filters,
            'departments' => $departments,
        ]);

    }

    public function departments(Documents $documents, Request $request, string $department)
    {


        $perPage = $request->input('per_page', 10);
        $status = $request->input('unit', null);
        $sortField = $request->input('sort_field', 'doc_name');
        $sortDirection = $request->input('sort_direction', 'desc');
        $sortName = $request->input('doc_name', null);
     
        $filters = [];
        if (!empty($status)) {
            $filters[] = [
                'id' => 'unit',
                'value' => $status
            ];
        }

        $departments = Department::select('unit', 'code')->get();
        $unit = $department;

        $products = Documents::query()
            // 1) always filter by the route-param
            ->where('unit', $department)

            // 2) only if $status is set, AND it’s an array, add the IN clause
            ->when($status, function ($query, $status) {
                if (is_array($status) && count($status)) {
                    $query->whereIn('unit', $status);
                }
            })->when(
                $sortName,
                fn($q, $c) =>
                $q->where('doc_name', 'like', "%{$c}%")
            )


            ->orderBy($sortField, $sortDirection)
            ->paginate(perPage: $perPage);


        // $matchedDocuments = Documents::where('unit', $documents->unit)->get();



        return Inertia::render('documents/departments', [
            'data' => $products,
            'departments' => $departments,
        ]);
    }

}
