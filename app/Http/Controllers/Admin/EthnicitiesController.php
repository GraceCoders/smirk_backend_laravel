<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

/**
 * Models
 * */

use App\Models\Ethnicity;


class EthnicitiesController extends Controller
{
    /**
     * ethnicitiesList
     *
     * @param  Request $request
     * @param  Ethnicity $ethnicity
     * @return void
     */
    public function ethnicitiesList(Request $request, Ethnicity $ethnicity)
    {
        try {
            $formData = $request->all();
            $limit = $formData['length'];
            $offset = $formData['start'];
            $ethnicitiesList['draw'] = $formData['draw'];
            $userDetails['recordsTotal'] = $ethnicity->count();
            if (isset($formData['search']['value'])) {
                $search = $formData['search']['value'];
                $userDetails['data'] = $ethnicity->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })->offset($offset)->limit($limit)->get();
                $userDetails['recordsFiltered'] = $ethnicity->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })->count();
            } else {
                $userDetails['data'] = $ethnicity->offset($offset)->limit($limit)->get();
                $userDetails['recordsFiltered'] = $ethnicity->count();
            }

            return json_encode($userDetails);
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * addEthnicity
     *
     * @param  Request $request
     * @param  Ethnicity $ethnicity
     * @return void
     */
    public function addEthnicity(Request $request, Ethnicity $ethnicity)
    {
        try {
            $getData = $request->all();
            unset($getData['_token']);
            $adminDetail = $ethnicity->create($getData);
            if ($adminDetail) {
                return redirect()->back()->with('success', 'Ethnicity added successfully');
            } else {
                return redirect()->back()->with('error', 'Not added');
            }
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * deleteEthnicity
     *
     * @param  Request $request
     * @param  Ethnicity $ethnicity
     * @return void
     */
    public function deleteEthnicity(Request $request, Ethnicity $ethnicity)
    {
        if ($ethnicity->where('id', $request->id)->delete()) {
            return 'success';
        } else {
            return 'error';
        };
    }
}