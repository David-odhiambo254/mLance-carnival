<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $pageTitle = 'Gig Pricing';
        $pricings  = Pricing::get();
        return view('admin.pricing.index', compact('pageTitle', 'pricings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $pricing = Pricing::findOrFail($id);
        $pricing->name = $request->name;
        $pricing->save();
        $notify[] = ['success', 'Gig pricing updated successfullay'];
        return back()->withNotify($notify);
    }
}
