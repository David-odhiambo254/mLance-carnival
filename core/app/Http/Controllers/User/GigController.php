<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Gig;
use App\Models\GigImage;
use App\Models\GigPricing;
use App\Models\Pricing;
use App\Models\Subcategory;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Validator;

class GigController extends Controller
{
    public function overview($id = 0)
    {
        $pageTitle = 'Gig Overview';
        $categories = Category::active()->with(['subcategories' => function ($query) {
            $query->active();
        }])->whereHas('subcategories', function ($q) {
            $q->active()->orderBy('name');
        })->orderBy('id')->get();
        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->first();
        return view('Template::user.gig.overview', compact('pageTitle', 'categories', 'gig'));
    }

    public function storeOverview(Request $request, $id = 0)
    {
        $validation  = Validator::make($request->all(), [
            'title'          => 'required',
            'description'    => 'required|string|not_in:<br>',
            'category_id'    => 'required',
            'subcategory_id' => 'nullable',
            'tags'           => 'required|array|min:1|max:5',
            'tags.*'         => 'nullable|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }


        $category = Category::active()->where('id', $request->category_id)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => "Category not found"
            ]);
        }

        $subcategory = Subcategory::active()->where('id', $request->subcategory_id)->first();

        if (!$subcategory) {
            return response()->json([
                'success' => false,
                'message' => "Subcategory not found"
            ]);
        }

        $user = auth()->user();

        if ($id) {
            $gig = Gig::where('user_id', $user->id)->where('id', $id)->firstOrFail();
            $gig->status = Status::GIG_PENDING;
        } else {
            $gig = new Gig();
            $gig->step           = 1;
        }

        $gig->user_id        = $user->id;
        $gig->title          = $request->title;
        $gig->description    = $request->description;
        $gig->category_id    = $request->category_id;
        $gig->subcategory_id = $request->subcategory_id;
        $gig->tags           = $request->tags;
        $gig->is_published   = Status::GIG_DRAFT;
        $gig->save();

        return response()->json([
            'success' => true,
            'redirect_url' => route('user.gig.pricing', $gig->id)
        ]);
    }

    public function pricing($id)
    {
        $pageTitle = 'Gig Pricing';
        $gig       = Gig::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $packages  = Pricing::with(['gigPricings' => function ($q) use ($gig) {
            $q->where('gig_id', $gig->id);
        }])->get();
        return view('Template::user.gig.pricing', compact('pageTitle', 'gig', 'packages'));
    }

    public function storePricing(Request $request, $id)
    {
        $validationRule = [
            'name.*'        => ['required'],
            'description.*' => ['required'],
            'price.*'       => ['required', 'numeric', 'gt:0'],
        ];
        $customMessages = [
            'price.*.required'      => "Gig pricing field is required",
        ];

        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$gig) {
            return response()->json([
                'success' => false,
                'message' => "Gig not found"
            ]);
        }

        $form = $gig->category->form;
        $rule = [];

        foreach ($form->form_data ?? [] as $data) {
            if ($data->is_required == 'required') {
                $rule = array_merge($rule, ['required']);
            } else {
                $rule = array_merge($rule, ['nullable']);
            }
            if ($data->type == 'select' || $data->type == 'checkbox' || $data->type == 'radio') {
                $rule = array_merge($rule, ['in:' . implode(',', $data->options)]);
            }
            if ($data->type == 'file') {
                $rule = array_merge($rule, ['mimes:' . $data->extensions]);
            }

            $checkVal = '';
            if ($data->type == 'checkbox') {
                $checkVal = '.*';
            }

            $validationRule[$data->label . '.*' . $checkVal] = $rule;
            $rule = [];
        }

        if (count($validationRule)) {
            $validation  = Validator::make($request->all(), $validationRule, $customMessages);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            }
        }

        $pricings = Pricing::get();

        $isUpdate = true;

        foreach ($pricings as $key => $pricing) {
            $pricingData = [];
            foreach ($request->except('name', 'description', 'price', '_token') as $priceKey => $priceData) {
                $pricingData[$priceKey] = @$priceData["$key"];
            }

            $gigPrice = GigPricing::where('gig_id', $gig->id)->where('pricing_id', $pricing->id)->first();
            if (!$gigPrice) {
                $isUpdate = false;
                $gigPrice = new GigPricing();
            }
            $gigPrice->pricing_id = $pricing->id;
            $gigPrice->gig_id = $gig->id;
            $gigPrice->name = $request->name[$key];
            $gigPrice->description = $request->description[$key];
            $gigPrice->price = $request->price[$key];
            $gigPrice->pricing_data = $pricingData;
            $gigPrice->save();
        }
        if (!$isUpdate) {
            $gig->step = 2;
            $gig->is_published   = Status::GIG_DRAFT;
        }
        $gig->status = Status::GIG_PENDING;
        $gig->save();

        return response()->json([
            'success'      => true,
            'is_update'    => $isUpdate,
            'redirect_url' => route('user.gig.requirement', $gig->id)
        ]);
    }

    public function requirement($id)
    {
        $pageTitle = 'Gig Requirement';
        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($gig->step < 2) {
            return abort(404);
        }
        return view('Template::user.gig.requirement', compact('pageTitle', 'gig'));
    }

    public function storeRequirement(Request $request, $id)
    {
        $validation  = Validator::make($request->all(), [
            'requirement'    => 'required|string|not_in:<br>',
        ], [
            'requirement.not_in' => 'Requirement field is required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }
        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$gig) {
            return response()->json([
                'success' => false,
                'message' => "Gig not found"
            ]);
        }


        $isUpdate = true;

        if (!$gig->requirement) {
            $gig->step  = 3;
            $isUpdate = false;
        }
        $gig->requirement = $request->requirement;
        $gig->status = Status::GIG_PENDING;
        $gig->save();

        return response()->json([
            'success'      => true,
            'is_update'    => $isUpdate,
            'redirect_url' => route('user.gig.faqs', $gig->id)
        ]);
    }

    public function faqs($id)
    {
        $pageTitle    = 'Gig FAQ';
        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        if ($gig->step < 3) {
            return abort(404);
        }
        return view('Template::user.gig.faqs', compact('pageTitle', 'gig'));
    }

    public function storeFAQs(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'question.*' => 'required',
            'answer.*'   => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$gig) {
            return response()->json([
                'success' => false,
                'message' => "Gig not found"
            ]);
        }

        $isUpdate = true;

        if (!$gig->faqs) {
            $gig->step = 4;
            $isUpdate = false;
        }

        $gig->faqs = [
            'question' => $request->question,
            'answer' => $request->answer,
        ];
        $gig->status = Status::GIG_PENDING;
        $gig->save();

        return response()->json([
            'success' => true,
            'is_update' => $isUpdate,
            'redirect_url' => route('user.gig.gallery', $gig->id)
        ]);
    }

    public function gallery($id)
    {
        $pageTitle    = 'Gig Gallery';
        $gig = Gig::where('id', $id)->with('images')->where('user_id', auth()->id())->firstOrFail();
        if ($gig->step < 4) {
            return abort(404);
        }
        return view('Template::user.gig.gallery', compact('pageTitle', 'gig'));
    }

    public function storeGallery(Request $request, $id)
    {
        $gig      = Gig::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$gig) {
            return response()->json([
                'success' => false,
                'message' => "Gig not found"
            ]);
        }

        $gigImage   = GigImage::where('gig_id', $gig->id)->first();
        $isRequired = $gigImage ? 'nullable' : 'required';

        $validation = Validator::make($request->all(), [
            'gallery'   => "$isRequired|array",
            'gallery.*' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        $gig->status = Status::GIG_PENDING;


        $isUpdate = true;
        if (!$gigImage) {
            $gig->step = 5;
            $isUpdate = false;
            $gig->is_published   = Status::GIG_DRAFT;
        }
        $gig->save();

        $this->insertImages($request, $gig);

        return response()->json([
            'success' => true,
            'is_update' => $isUpdate,
            'redirect_url' => route('user.gig.publish', $gig->id)
        ]);
    }

    protected function insertImages($request, $gig)
    {


        $path = getFilePath('gig');

        if ($request->hasFile('gallery')) {
            $size = getFileSize('gig');
            $images = [];

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $hasImages = $request->file('gallery');

            foreach ($hasImages as $file) {
                try {
                    $name = fileUploader($file, $path, $size);
                    $image        = new GigImage();
                    $image->gig_id = $gig->id;
                    $image->name = $name;
                    $images[]     = $image;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload gig gallery images'];
                    return back()->withNotify($notify);
                }
            }
            $gig->images()->saveMany($images);
        }
    }


    public function removeGallery($id)
    {
        $gigImage = GigImage::where('id', $id)->firstOrFail();
        $gig      = Gig::where('id', $gigImage->gig_id)->where('user_id', auth()->id())->firstOrFail();
        $path     = getFilePath('gig') . '/' . $gigImage->name;
        $gigImage->delete();
        @unlink($path);

        $notify[] = ['success', "Gig image remove successfully"];
        return back()->withNotify($notify);
    }


    public function publish($id)
    {
        $pageTitle    = 'Gig Gallery';
        $gig = Gig::where('id', $id)->with('images')->where('user_id', auth()->id())->firstOrFail();
        if ($gig->step < 5) {
            return abort(404);
        }
        return view('Template::user.gig.publish', compact('pageTitle', 'gig'));
    }

    public function gigPublished(Request $request, $id)
    {
        $gig = Gig::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$gig) {
            return response()->json([
                'success' => false,
                'message' => "Gig not found"
            ]);
        }

        $gig->is_published = $request->is_published == 0 ? Status::GIG_DRAFT : Status::GIG_PUBLISHED;
        $gig->status = Status::GIG_PENDING;
        $gig->save();

        return response()->json([
            'success'      => true,
            'status'       => $gig->is_published,
            'redirect_url' => route('gig.explore', $gig->id)
        ]);
    }

    public function gigList()
    {
        $pageTitle = 'My Gigs';
        $gigs = Gig::where('user_id', auth()->id())->with('user', 'category', 'subcategory', 'gigPricing', 'images')->latest()->paginate(getPaginate());
        return view('Template::user.gig.list', compact('pageTitle', 'gigs'));
    }

    public function publishedStatus($id)
    {
        $gig     = Gig::findOrFail($id);
        if ($gig->is_published == Status::GIG_DRAFT) {
            $gig->is_published = Status::GIG_PUBLISHED;
            $message       = 'Gig published successfully';
        } else {
            $gig->is_published = Status::GIG_DRAFT;
            $message       = 'Gig drafted successfully';
        }
        $gig->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
