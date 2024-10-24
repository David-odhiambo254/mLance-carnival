<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\GigPricing;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(Request $request, $gigId, $pricingId)
    {
        $request->validate([
            'quotes' => 'required|min:2'
        ]);

        $gig =  Gig::where('id', $gigId)->approved()->firstOrFail();
        $user = auth()->user();

        if ($gig->user->id == $user->id) {
            $notify[] = ['error', 'Invalid order! You can\'t buy your service'];
            return back()->withNotify($notify);
        }

        $package = GigPricing::where('gig_id', $gig->id)->where('id', $pricingId)->first();

        if (!$package) {
            $notify[] = ['error', 'Invalid package'];
            return back()->withNotify($notify);
        }

        $order                 = new Order();
        $order->gig_id         = $gig->id;
        $order->gig_pricing_id = $package->id;
        $order->user_id        = $user->id;
        $order->number         = getTrx();
        $order->price          = $package->price;
        $order->quotes         = $request->quotes;
        $order->save();

        $project            = new Project();
        $project->gig_id    = $gig->id;
        $project->order_id  = $order->id;
        $project->buyer_id  = $user->id;
        $project->seller_id = $gig->user_id;
        $project->save();

        $projectActivity             = new ProjectActivity();
        $projectActivity->user_id    = $user->id;
        $projectActivity->project_id = $project->id;
        $projectActivity->message    = 'Order request created';
        $projectActivity->save();

        $seller = $gig->user;
        $buyer  = $order->user;

        notify($seller, 'ORDER_REQUESTED', [
            'title'  => $order->gig->title,
            'buyer'  => $buyer->fullname,
            'amount' => showAmount($order->price, currencyFormat:false) . ' ' . gs('cur_text'),
        ]);

        $notify[] = ['success', 'Order request sent successfully.'];
        return to_route('user.project.details', $project->id)->withNotify($notify);
    }
}
