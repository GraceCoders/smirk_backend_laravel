<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

/**
 * Models
 * */

use App\Models\Preference;

class PreferencesController extends Controller
{

    /**
     * ethnicitiesList
     *
     * @param  Request $request
     * @param  Preferences $preference
     * @return void
     */
    public function preferencesList(Request $request, Preference $preference)
    {
        try {
            $formData = $request->all();
            $limit = $formData['length'];
            $offset = $formData['start'];
            $ethnicitiesList['draw'] = $formData['draw'];
            $userDetails['recordsTotal'] = $preference->count();
            if (isset($formData['search']['value'])) {
                $search = $formData['search']['value'];
                $userDetails['data'] = $preference->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })->offset($offset)->limit($limit)->get();
                $userDetails['recordsFiltered'] = $preference->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })->count();
            } else {
                $userDetails['data'] = $preference->offset($offset)->limit($limit)->get();
                $userDetails['recordsFiltered'] = $preference->count();
            }

            return json_encode($userDetails);
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * addPreference
     *
     * @param  Request $request
     * @param  Preference $preference
     * @return void
     */
    public function addPreference(Request $request, Preference $preference)
    {
        try {
            $getData = $request->all();
            unset($getData['_token']);
            $adminDetail = $preference->create($getData);
            if ($adminDetail) {
                return redirect()->back()->with('success', 'Preference added successfully');
            } else {
                return redirect()->back()->with('error', 'Not added');
            }
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * deletePreference
     *
     * @param  Request $request
     * @param  Preference $preference
     * @return void
     */
    public function deletePreference(Request $request, Preference $preference)
    {
        if ($preference->where('id', $request->id)->delete()) {
            return 'success';
        } else {
            return 'error';
        };
    }
}