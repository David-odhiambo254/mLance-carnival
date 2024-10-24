<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Category;
use App\Models\Pricing;
use App\Models\Gig;
use App\Models\Project;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\UserPortfolio;
use Illuminate\Http\Request;

class GigController extends Controller
{

    public function index()
    {
        $pageTitle = 'Gigs';
        $query     = $this->gigData();
        $gigs      = $query->paginate(getPaginate(18));
        $categories = Category::active()->get();
        return view('Template::gig.index', compact('pageTitle', 'gigs', 'categories'));
    }

    public function details($id)
    {
        $pageTitle = 'Gig Details';
        $query     = Gig::where('id', $id);
        if (auth()->check()) {
            $query->where(function ($query) {
                $query->where('user_id', auth()->id())->orWhere('status', Status::GIG_APPROVED);
            });
        } else {
            $query->approved();
        }

        $gig     = $query->with('user', 'category', 'images', 'reviews.user')->withAvg('reviews', 'rating')->withCount('reviews')->first();
        if (!$gig) {
            $notify[] =  ['error', 'Gig not found!'];
            return back()->withNotify($notify);
        }
        $user = $gig->user;
        $reviews = $gig->reviews()->with('user')->orderBy('id', 'DESC')->limit(10)->get();


        $packages      = Pricing::with(['gigPricings' => function ($q) use ($gig) {
            $q->where('gig_id', $gig->id);
        }])->get();


        if ($gig->user_id != @auth()->user()->id) {
            $gig->views += 1;
            $gig->save();
        }

        $queueOrders = Project::where('gig_id', $gig->id)->where('status', '!=', Status::PROJECT_REJECTED)->where('status', '!=', Status::PROJECT_COMPLETED)->count();

        $relatedGigs = Gig::approved()
            ->where('id', '!=', $gig->id)
            ->where('subcategory_id', $gig->subcategory_id)
            ->inRandomOrder()->with('user', 'category', 'images', 'gigPricing', 'reviews')->orderBy('id', 'DESC')->limit(20)->withAvg('reviews', 'rating')
            ->get();

        //start-seo//
        $gigImage = @$gig->images->first()->name;
        $seoContents['keywords']           = $gig->title ?? [];
        $seoContents['keywords']           = $gig->tags ?? [];
        $seoContents['social_title']       = $gig->title;
        $seoContents['description']        = strLimit(strip_tags($gig->description), 200);
        $seoContents['social_description'] = strLimit(strip_tags($gig->description), 150);
        $seoContents['image']              = getImage(getFilePath('gig') . '/' . $gigImage, '770x400');
        $seoContents['image_size']         = '400x300';
        $seoContents['author']             = $gig->user->fullname ?? '';
        $seoContents['email']              = $gig->user->email;
        $seoContents['avgRating']          = showAmount($gig->avg_rating);
        $seoContents['bestRating']         = $gig->reviews->max('rating');
        $seoContents['worstRating']        = $gig->reviews->min('rating');
        $seoContents['countReview']        = $gig->reviews_count;
        $seoContents['publishedAt']        = showDateTime($gig->created_at);

        $gigs         = Gig::approved()->where('user_id', $user->id)->withAvg('reviews', 'rating');

        $query = Review::whereHas('gig', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('id', 'DESC');

        $reviewCount  = (clone $query)->count();
        $totalGig     = (clone $gig)->count();
        $totalProject = Project::where('seller_id', $user->id)->completed()->count();
        $avgRating    = $gigs->sum('avg_rating') ?? 1 / $reviewCount;

        return view('Template::gig.explore', compact('pageTitle', 'gig', 'packages', 'queueOrders', 'relatedGigs', 'reviews', 'seoContents', 'reviewCount', 'avgRating', 'totalGig', "totalProject"));
    }



    public function reviews(Request $request, $id)
    {
        $reviews = Review::with('user')->where('gig_id', $id)->orderBy('id', 'DESC')->skip($request->length)->take(10)->get();
        $html = view('Template::gig.review', ['reviews' => $reviews])->render();
        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $reviews->count(),
        ]);
    }

    public function profile($id)
    {
        $user         = User::active()->findOrFail($id);
        $lastDelivery = Project::completed()->where('seller_id', $user->id)->latest()->first();
        $pageTitle    = $user->firstname . "'s" . " " . "Profile";
        $gigQuery     = Gig::approved()->where('user_id', $id)->with('user', 'images', 'gigPricing', 'reviews')->withAvg('reviews', 'rating');
        $gigs         = (clone $gigQuery)->orderBy('id', 'DESC')->paginate(getPaginate());

        $query = Review::whereHas('gig', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('id', 'DESC');

        $reviewCount = (clone $query)->count();
        $reviews     = (clone $query)->take(10)->get();
        $avgRating   = (clone $gigQuery)->sum('avg_rating') ?? 1 / $reviewCount;

        $portfolio           = UserPortfolio::where('user_id', $user->id);
        $totalPortfolioCount = (clone  $portfolio)->count();
        $portfolios          = (clone  $portfolio)->take(5)->orderBy('id', 'DESC')->get();

        $seoContents['keywords']           = [];
        $seoContents['social_title']       = $user->tagline;
        $seoContents['description']        = strLimit(strip_tags($user->description), 200);
        $seoContents['social_description'] = strLimit(strip_tags($user->description), 150);
        $seoContents['image']              = getImage(getFilePath('userProfile') . '/' . @$user->image, '400x400');
        $seoContents['image_size']         = '400x400';
        $seoContents['author']             = $user->fullname ?? '';
        $seoContents['email']              = $user->email;
        $seoContents['avgRating']          = '';
        $seoContents['bestRating']         = '';
        $seoContents['worstRating']        = '';
        $seoContents['countReview']        = '';
        $seoContents['publishedAt']        = showDateTime($user->created_at);

        return view('Template::gig.profile', compact('pageTitle', 'gigs', 'user', 'lastDelivery', 'reviews', 'reviewCount', 'avgRating', 'totalPortfolioCount', 'portfolios', 'seoContents'));
    }

    public function portfolio($id)
    {
        $user         = User::find($id);
        $pageTitle    = $user->firstname . "'s" . " " . "Portfolio";
        $portfolios = UserPortfolio::where('user_id', $id)->paginate(getPaginate());

        $userImage =  $user->image;
        $seoContents['keywords']           = [];
        $seoContents['social_title']       = $user->tagline;
        $seoContents['description']        = strLimit(strip_tags($user->description), 200);
        $seoContents['social_description'] = strLimit(strip_tags($user->description), 150);
        $seoContents['image']              = getImage(getFilePath('userProfile') . '/' . $userImage, '400x400');
        $seoContents['image_size']         = '400x400';
        $seoContents['author']             = $user->fullname ?? '';
        $seoContents['email']              = $user->email;
        $seoContents['avgRating']          = '';
        $seoContents['bestRating']         = '';
        $seoContents['worstRating']        = '';
        $seoContents['countReview']        = '';
        $seoContents['publishedAt']        = showDateTime($user->created_at);

        return view('Template::gig.portfolio', compact('pageTitle', 'user', 'portfolios', 'seoContents'));
    }


    public function portfolioDetails($id)
    {
        $pageTitle = "Portfolio Details";
        $portfolio = UserPortfolio::where('id', $id)->with('user')->firstOrFail();

        //start-seo//
        $seoContents['keywords']           =  [];
        $seoContents['social_title']       = $portfolio->title;
        $seoContents['description']        = strLimit(strip_tags($portfolio->description), 200);
        $seoContents['social_description'] = strLimit(strip_tags($portfolio->description), 150);
        $seoContents['image']              = getImage(getFilePath('userPortfolio') . '/' . $portfolio->image, '715x430');
        $seoContents['image_size']         = '715x430';
        $seoContents['author']             = $portfolio->user->fullname ?? '';
        $seoContents['email']              = $portfolio->user->email;
        $seoContents['avgRating']          = '';
        $seoContents['bestRating']         = '';
        $seoContents['worstRating']        = '';
        $seoContents['countReview']        = '';
        $seoContents['publishedAt']        = showDateTime($portfolio->created_at);
        //ends-seo//
        $portfolios = UserPortfolio::where('user_id', $portfolio->user_id)->take(5)->where('id', '!=', $id)->get();
        return view('Template::gig.portfolio_details', compact('pageTitle', 'portfolio', 'seoContents', 'portfolios'));
    }

    public function gigsReviews(Request $request, $userID)
    {
        $myReviews = Review::whereIn('gig_id', function ($query) use ($userID) {
            $query->select('id')
                ->from(with(new Gig)->getTable())
                ->where('user_id', $userID);
        })
            ->orderBy('id', 'DESC')
            ->skip($request->length)
            ->take(10)
            ->get();
        $reviewCount = $myReviews->count();
        $html = view('Template::gig.review', ['reviews' => $myReviews])->render();
        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $reviewCount,
        ]);
    }

    public function subcategoryWise($id)
    {
        $subcategory = Subcategory::active()->with(['category' => function ($q) {
            $q->active();
        }])->findOrFail($id);
        $pageTitle =  $subcategory->name;
        $query = $this->gigData();
        $gigs  =  $query->where('subcategory_id', $subcategory->id)->orderBy('id', 'DESC')->paginate(getPaginate(18));

        return view('Template::gig.index', compact('pageTitle', 'gigs', 'subcategory'));
    }

    public function categoryWise($id)
    {
        $category  = Category::active()->findOrFail($id);
        $pageTitle =  $category->name;
        $query = $this->gigData();
        $gigs  =  $query->where('category_id', $category->id)->orderBy('id', 'DESC')->paginate(getPaginate(18));
        return view('Template::gig.index', compact('pageTitle', 'gigs', 'category'));
    }

    protected  function gigData()
    {
        $query     = Gig::searchable(['title', 'tags'])->approved();

        $request = request();

        if ($request->search) {
            $query->where('title', "Like", "%" . $request->search . "%");
        }

        if ($request->rating && $request->rating != 'all') {
            $query->where('avg_rating', ">=", $request->rating);
        }

        if ($request->min_price) {
            $query->whereHas('gigPricing', function ($q) use ($request) {
                $q->where('price', ">=", $request->min_price);
            });
        }

        if ($request->max_price) {
            $query->whereHas('gigPricing', function ($q) use ($request) {
                $q->where('price', "<=", $request->max_price);
            });
        }

        if (request()->sorting == 'best_selling') {
            $query = $query->whereHas('projects', function ($q) {
                $q->completed();
            })->withCount(['projects as completion_count' => function ($q) {
                $q->completed();
            }])->orderByDesc('completion_count');
        } else if (request()->sorting == 'top_rating') {
            $query = $query->where('avg_rating', '>', 0)
                ->orderByDesc('avg_rating');
        } else if (request()->sorting == 'top_reviews') {
            $query = $query
                ->withCount('reviews')
                ->has('reviews')
                ->orderByDesc('reviews_count');
        }
        return $query->with('user', 'category', 'images', 'gigPricing', 'reviews')->withAvg('reviews', 'rating');
    }
}
