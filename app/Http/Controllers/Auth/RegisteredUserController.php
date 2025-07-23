<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        $dept = Department::latest()->get();
        $org = Organization::latest()->get();
        return Inertia::render('auth/Register', ['org' => $org, 'dept' => $dept]);
    }

    public function view()
    {
        $users = User::latest()->get();
        $dept = Department::latest()->get();
        $org = Organization::latest()->get();
        return Inertia::render(
            'settings/users',
            ['data' => $users, 'dept' => $dept, 'org' => $org]
        );
    }


    public function update(Request $request, User $user){
      
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
            'password' => [ 'sometimes', 'nullable','confirmed', Rules\Password::defaults()],
            'org_name' => 'required|string|max:50',
            'user_level' => 'required|string|max:255',
        ]);

        
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'org_name' => $request->input('org_name'),
            'user_level' => $request->input('user_level'),
        ]);

         if ($request->filled('password')) {
        $user['password'] = Hash::make($request->password);
    }

             return redirect('settings/users')->with('message', 'User Updated Successfully');
    }

    
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'org_name' => 'required|string|max:50',
            'user_level' => 'required|string|max:255',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'org_name' => $request->org_name,
            'user_level' => $request->user_level
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
