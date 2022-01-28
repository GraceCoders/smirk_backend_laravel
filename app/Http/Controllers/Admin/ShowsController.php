<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Models
 * */

use App\Models\Show;

class ShowsController extends Controller
{
    /**
     * showsList
     *
     * @param  Request $request
     * @param  Show $show
     * @return void
     */
    public function showsList(Request $request, Show $show)
    {
        try {
            $formData = $request->all();
            $limit = $formData['length'];
            $offset = $formData['start'];
            $ethnicitiesList['draw'] = $formData['draw'];
            $userDetails['recordsTotal'] = $show->count();
            $userDetails['data'] = $show->offset($offset)->limit($limit)->get();
            $userDetails['recordsFiltered'] = $show->count();
            return json_encode($userDetails);
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * addShow
     *
     * @param  Request $request
     * @param  Show $show
     * @return void
     */
    public function addShow(Request $request, Show $show)
    {
        try {
            $formData = $request->all();
            unset($formData['_token']);
            if ($request->file('show_icon')) {
                $path = Storage::disk('public')->put(config('filesdirectory.show'), $request->file('show_icon'));
                $formData['show_icon'] = url('storage/' . $path);
            }
            $adminDetail = $show->create($formData);
            if ($adminDetail) {
                return redirect()->back()->with('success', 'Show added successfully');
            } else {
                return redirect()->back()->with('error', 'Not added');
            }
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * deleteShow
     *
     * @param  Request $request
     * @param  Show $show
     * @return void
     */
    public function deleteShow(Request $request, Show $show)
    {
        if ($show->where('id', $request->id)->delete()) {
            return 'success';
        } else {
            return 'error';
        };
    }
}