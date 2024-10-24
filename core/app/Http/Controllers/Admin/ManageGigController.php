<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\Pricing;
use App\Models\User;

class ManageGigController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Gigs';
        $gigs      = $this->gigData();
        return view('admin.gig.index', compact('pageTitle', 'gigs'));
    }

    public function pending()
    {
        $pageTitle = "Pending Gigs";
        $gigs = $this->gigData('pending');
        return view('admin.gig.index', compact('pageTitle', 'gigs'));
    }
    public function approved()
    {
        $pageTitle = "Approved Gigs";
        $gigs = $this->gigData('approved');
        return view('admin.gig.index', compact('pageTitle', 'gigs'));
    }
    public function rejected()
    {
        $pageTitle = "Rejected Gigs";
        $gigs = $this->gigData('rejected');
        return view('admin.gig.index', compact('pageTitle', 'gigs'));
    }

    protected function gigData($scope = null)
    {
        if ($scope) {
            $gigs = Gig::$scope();
        } else {
            $gigs = Gig::query();
        }

        return $gigs->searchable(['title', 'user:username', 'category:name', 'subcategory:name'])->filter(['status', 'is_published'])->with('user', 'category', 'subcategory', 'gigPricing', 'images')->orderBy('id', 'DESC')->paginate(getPaginate());
    }

    public function details($id)
    {
        $pageTitle = 'Gig Details';
        $gig       = Gig::with('user', 'category', 'images')->findOrFail($id);
        $packages  = Pricing::with(['gigPricings' => function ($q) use ($gig) {
            $q->where('gig_id', $gig->id);
        }])->get();
        return view('admin.gig.details', compact('pageTitle', 'gig', 'packages'));
    }

    public function approve($id)
    {
        $gig  = Gig::whereIn("status", [Status::GIG_REJECTED, Status::GIG_PENDING])->findOrFail($id);
        $user = $gig->user;
        $user = User::active()->where('id', $gig->user_id)->first();
        if (!$user) {
            $notify[] = ['error',  "Gig owner is banned now!"];
            return back()->withNotify($notify);
        }
        $gig->status = Status::GIG_APPROVED;
        $gig->save();

        notify($user, 'GIG_APPROVED', [
            'title'    => $gig->title,
        ]);

        $notify[] = ['success',  "Gig approved successfully"];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $gig  = Gig::whereIn("status", [Status::GIG_APPROVED, Status::GIG_PENDING])->findOrFail($id);
        $gig->status = Status::GIG_REJECTED;
        $gig->save();

        notify($gig->user, 'GIG_REJECTED', [
            'title'    => $gig->title,
        ]);

        $notify[] = ['success',  "Gig rejected successfully"];
        return back()->withNotify($notify);
    }
}
