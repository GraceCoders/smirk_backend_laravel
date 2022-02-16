<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Catgory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $card  = Category::paginate(20);
            return view('category.list', compact('card'));
        } catch (Exception $exception) {
            return view('exceptions', compact('exception'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Category();
        $data->name = $request->name;
        $data->save();
        return redirect('/catgory/list')->with('success', 'Category add Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Catgory  $catgory
     * @return \Illuminate\Http\Response
     */
    public function show(Category $catgory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Catgory  $catgory
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $catgory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Catgory  $catgory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $catgory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Catgory  $catgory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Category::findOrFail($id);
        $user->delete();
        return redirect('/catgory/list')->with('success', 'Category Deleted Successfully');;
    }
}
