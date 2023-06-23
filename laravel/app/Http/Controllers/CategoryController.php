<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CreateCategoriesValidation;
use App\Http\Requests\Categories\UpdateCategoriesValidation;
use App\Http\Requests\Roles\CreateRolesValidation;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoriesValidation $categoriesValidation)
    {
        $request = $categoriesValidation->validated();
        unset($request['photo']);
        # public/sdfsdfsdfsd.jpg
        $photo = $categoriesValidation->file('photo')->store('public/categories');
        # Explode => / => public/sdfsdfsdfsd.jpg => ['public', 'sdfsdfsdfsd.jpg']
        $request['photo'] = explode('/',$photo)[2];
        Category::create($request);
        return redirect()->route('admin.categories.index')->with(['add' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoriesValidation $updateCategoriesValidation, Category $category)
    {
        $request = $updateCategoriesValidation->validated();
        if (isset($request['photo'])){
            unset($request['photo']);
            # public/sdfsdfsdfsd.jpg
            $photo = $updateCategoriesValidation->file('photo')->store('public/categories');
            # Explode => / => public/sdfsdfsdfsd.jpg => ['public', 'sdfsdfsdfsd.jpg']
            $request['photo'] = explode('/',$photo)[2];
        }
        $category->update($request);
        return redirect()->route('admin.categories.index')->with(['update' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with(['destroy' => true]);
    }
}
