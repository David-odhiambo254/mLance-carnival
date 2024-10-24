<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Category;
use App\Models\Form;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle  = 'All Categories';
        $categories = Category::searchable(['name'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }


    public function store(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate(
            [
                'name'  => 'required',
                'image' => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            ]
        );
        if ($id) {
            $category     = Category::findOrFail($id);
            $notification = 'Category updated successfully';
        } else {
            $category     = new Category();
            $notification = 'Category added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), @$category->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload category image'];
                return back()->withNotify($notify);
            }
        }

        $category->name = $request->name;
        $category->save();
        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Category::changeStatus($id);
    }

    public function feature($id)
    {
        return Category::changeStatus($id, 'is_featured');
    }

    public function pricingFormGenerate($id)
    {
        $category  = Category::findOrFail($id);
        $pageTitle = 'Generate Pricing Heads for: ' . $category->name . " category";
        $pricing   = Form::where('id', $category->form_id)->where('act', 'pricing_form')->first();
        return view('admin.category.pricing', compact('pageTitle', 'pricing', 'category'));
    }

    public function pricingFormGenerateUpdate(Request $request, $id)
    {

        $category            = Category::findOrFail($id);

        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'], $generatorValidation['messages']);

        $exist = Form::where('id', $category->form_id)->where('act', 'pricing_form')->exists();

        if ($exist) {
            $form = $formProcessor->generate('pricing_form', true, 'id', $category->form_id);
        } else {
            $form              = $formProcessor->generate('pricing_form');
            $category->form_id = $form->id;
        }

        foreach ($form->form_data as $value) {
            if ($value->type == 'file') {
                $notify[] = ['error', 'File type isn\'t supported, Please complete the form without including the file.'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $category->save();

        $notify[] = ['success', 'Pricing form updated successfully'];
        return back()->withNotify($notify);
    }
}
