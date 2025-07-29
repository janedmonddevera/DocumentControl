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
        // $query = Documents::query();

        // // Apply filters if present
        // if ($request->has('unit')) {
        //     $query->whereIn('unit', (array) $request->input('unit'));
        // }

        // if ($request->has('sort_field') && $request->has('sort_direction')) {
        //     $query->orderBy($request->input('sort_field'), $request->input('sort_direction'));
        // }

        // $documents = $query->paginate($request->input('per_page', 10))->withQueryString();

        // return Inertia::render('documents/index', [
        //     'data' => $documents,
        //     'filters' => [
        //         'unit' => $request->input('unit', []),
        //         'sort_field' => $request->input('sort_field', null),
        //         'sort_direction' => $request->input('sort_direction', null),
        //         'per_page' => $request->input('per_page', 10),
        //     ],
        // ]);

       

        $perPage = $request->input('per_page', 10);
        $status = $request->input('unit', null);
        $sortField = $request->input('sort_field', 'doc_name');
        $sortDirection = $request->input('sort_direction', 'desc');
        $filters = [];
        if (!empty($status)) {
            $filters[] = [
                'id' => 'unit',
                'value' => $status
            ];
        }


        $departments = Department::select('unit','code')->get();

        $products = Documents::query()->when($status, function ($query, $status) {
            if (is_array($status) && !empty($status)) {
                $query->whereIn('unit', $status);
            }
        })->orderBy($sortField, $sortDirection)->paginate(perPage: $perPage);
        return Inertia::render('documents/Index', [
            'data' => $products,
            'filter' => $filters,
            'departments' => $departments,
        ]);

    }

       public function departments(Documents $documents)
{
    $departments = Department::select('unit', 'code')->get();

    $matchedDocuments = Documents::where('unit', $documents->unit)->get();

    return Inertia::render('documents/departments', [
        'data' => $matchedDocuments,
        'departments' => $departments,
    ]);
}

}
