<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{

    public function index()
    {
        $pageTitle     = 'All Subcategories';
        $categories    = Category::active()->orderBy('name')->get();
        $subcategories = Subcategory::searchable(['name'])->with('category')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.subcategory.index', compact('pageTitle', 'categories', 'subcategories'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate(
            [
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required',
            ]
        );

        if ($id) {
            $subcategory     = Subcategory::findOrFail($id);
            $notification = 'Subcategory updated successfully';
        } else {
            $subcategory     = new Subcategory();
            $notification = 'Subcategory added successfully';
        }

        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->save();
        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Subcategory::changeStatus($id);
    }

    public function feature($id)
    {
        return Subcategory::changeStatus($id, 'is_featured');
    }

    public function popular($id)
    {
        return Subcategory::changeStatus($id, 'is_popular');
    }
}
