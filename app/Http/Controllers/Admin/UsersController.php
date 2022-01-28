<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

/**
 * Models
 * */

use App\Models\User;

class UsersController extends Controller
{

    /**
     * login
     *
     * @param  Request $request
     * @return void
     */
    public function login(Request $request, User $user)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!(Auth::attempt($credentials))) {
                return redirect('/')->with('error', trans("Messages.InvalidCredentials"));
            }
            return redirect('dashboard');
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    public function dashboard()
    {
        try {
            return view('pages/dashboard');
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    public function usersList(Request $request, User $user)
    {
        try {
            $formData = $request->all();
            $limit = $formData['length'];
            $offset = $formData['start'];
            $ethnicitiesList['draw'] = $formData['draw'];
            $userDetails['recordsTotal'] = $user->count();
            if (isset($formData['search']['value'])) {
                $search = $formData['search']['value'];
                $userDetails['data'] = $user->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%");
                })->offset($offset)->limit($limit)->get();
                $userDetails['recordsFiltered'] = $user->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%");
                })->count();
            } else {
                $userDetails['data'] = $user->offset($offset)->limit($limit)->get();
                $userDetails['recordsFiltered'] = $user->count();
            }

            return json_encode($userDetails);
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}