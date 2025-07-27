<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Masterlist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MasterlistController extends Controller
{
    public function index()
    {
        $masterlist = Masterlist::latest()->get();
        $user_level = auth()->user()->user_level;
        $units = Department::select('unit', 'code')->get();

        return Inertia::render(
            'masterlist/index',
            [
                'data' => $masterlist,
                'user_level' => $user_level,
                'units' => $units,
            ]
        );
    }




    public function create()
    {
        return Inertia::render(
            'masterlist/create',
            []
        );
    }

    public function store(Request $request)
    {
    

        $orgName = auth()->user()->org_name;
        $email = auth()->user()->email;

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'unit' => 'required|string|max:10',
            'type' => 'required|string|max:5',
            'type_name' => 'required|string|max:255',
        ]);

        // Inject the org_name into the validated data
        $data['org_name'] = $orgName;
        $data['username'] = $email;

        $data['seq'] = str_pad(Masterlist::where('type', $data['type'])->count() + 1, 2, '0', STR_PAD_LEFT);


        $data['doc_code'] = strtoupper($data['org_name']) . $data['unit'] . '-' . $data['type'] . '-' . $data['seq'];


        Masterlist::create($data);

        return redirect()->route('masterlist.index')->with('message', 'Document Added Successfully');
    }

    public function edit(Masterlist $masterlist)
    {
        return Inertia::render(
            'masterlist/edit',
            compact('masterlist')
        );
    }

    public function update(Request $request, Masterlist $masterlist)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'unit' => 'required|string|max:10',
            'type' => 'required|string|max:5',
            'type_name' => 'required|string|max:255',

        ]);

        $seq = $masterlist->seq;
        $seq = str_pad($seq, 2, '0', STR_PAD_LEFT);
        $orgName = auth()->user()->org_name;

        $request['doc_code'] = strtoupper($orgName) . $request['unit'] . '-' . $request['type'] . '-' . $seq;
        $masterlist->update([
            'title' => $request->input('title'),
            'doc_code' => $request->input('doc_code'),
            'unit' => $request->input('unit'),
            'type' => $request->input('type'),
            'type_name' => $request->input('type_name'),
        ]);

        // return redirect()->route('masterlist.index')->with('message', 'Masterlist Updated Successfully');
        return redirect()->action([MasterlistController::class, 'index'])->with('message', 'Masterlist Updated Successfully');
    }

    public function destroy(Masterlist $masterlist)
    {

        $masterlist->delete();

        return redirect()->route('masterlist.index')->with('message', 'Product Deleted Successfully');
    }
}
