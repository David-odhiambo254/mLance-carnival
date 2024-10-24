<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function pending()
    {
        $pageTitle = "Pending Projects";
        $projects = $this->projectData('pending');
        return view('Template::user.project.index', compact('pageTitle', 'projects'));
    }

    public function accepted()
    {
        $pageTitle = "Accepted Projects";
        $projects = $this->projectData('accepted');
        return view('Template::user.project.index', compact('pageTitle', 'projects'));
    }

    public function rejected()
    {
        $pageTitle = "Rejected Projects";
        $projects = $this->projectData('rejected');
        return view('Template::user.project.index', compact('pageTitle', 'projects'));
    }

    public function reported()
    {
        $pageTitle = "Reported Projects";
        $projects = $this->projectData('reported');
        return view('Template::user.project.index', compact('pageTitle', 'projects'));
    }

    public function completed($id = 0) //id-gig sales-count//
    {
        $pageTitle = "Completed Projects";
        $userId = auth()->id();
        if ($id) {
            $projects = Project::completed()->where('gig_id', $id)->where(function ($query) use ($userId) {
                $query->orWhere('seller_id', $userId)->orWhere('buyer_id', $userId);
            })->with('gig', 'order', 'buyer', 'seller')->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $projects = $this->projectData('completed');
        }


        return view('Template::user.project.index', compact('pageTitle', 'projects'));
    }

    protected function projectData($scope)
    {
        $userId = auth()->id();
        return Project::$scope()->where(function ($query) use ($userId) {
            $query->orWhere('seller_id', $userId)->orWhere('buyer_id', $userId);
        })->with(['gig', 'order', 'buyer', 'seller'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function details($id)
    {
        $pageTitle = "Project Details";
        $project =  $this->historyData($id);
        return view('Template::user.project.details', compact('pageTitle', 'project'));
    }

    public function files($id)
    {
        $pageTitle = "Files";
        $project   = $this->historyData($id);
        return view('Template::user.project.files', compact('pageTitle', 'project'));
    }

    protected function historyData($id)
    {
        $userId = auth()->id();
        return Project::where(function ($query) use ($userId) {
            $query->orWhere('seller_id', $userId)->orWhere('buyer_id', $userId);
        })->with('gig', 'order', 'seller', 'buyer', 'projectActivities.admin', 'review')->findOrFail($id);
    }

    public function uploadFiles(Request $request, $id)
    {

        $validation  = Validator::make($request->all(), [
            'files'             => ['required', 'array'],
            'files.*'            => ['file',new FileTypeValidate(['jpg', 'jpeg', 'png', 'zip', 'rar', 'pdf', '3gp', 'mpeg3', 'x-mpeg-3', 'mp4', 'mpeg', 'mpkg', 'doc', 'docx', 'gif', 'txt', 'svg', 'wav', 'xls', 'xlsx', '7z'])],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        $path = getFilePath('files');
        $user = auth()->user();

        $activity = new ProjectActivity();
        $activity->project_id = $id;
        $activity->user_id = $user->id;

        $allFiles = [];
        foreach ($request->file('files') as $file) {
            try {
                $directory = date("Y") . "/" . date("m") . "/" . date("d");
                $path = getFilePath('files') . '/' . $directory;
                $allFiles[] =  $directory . '/' . fileUploader($file, $path);
            } catch (\Exception $exp) {
                return response()->json([
                    'success' => false,
                    'message' => 'File could not upload'
                ]);
            }
        }

        $activity->files = $allFiles;
        $activity->save();

        return response()->json([
            'success'  => true,
            'activity' => $activity,
        ]);
    }

    public function downloadFile($id, $file)
    {
        $userId = auth()->id();
        $project = Project::where('id', $id)->where(function ($query) use ($userId) {
            $query->orWhere('seller_id', $userId)->orWhere('buyer_id', $userId);
        })->with('gig')->first();

        if (!$project) {
            $notify[] = ['error', 'Invalid download action'];
            return back()->withNotify(
                $notify
            );
        }

        $path = getFilePath('files');
        $file = decrypt($file);

        $full_path = $path . '/' . $file;
        $title = slug(substr($project->gig->title, 0, 20));
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($full_path);
    }

    public function message(Request $request, $id)
    {
        $validation  = Validator::make($request->all(), [
            'message'  => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => "Invalid activity"
            ]);
        }

        $user = auth()->user();

        $activity = new ProjectActivity();
        $activity->project_id = $id;
        $activity->user_id = $user->id;
        $activity->message = $request->message;
        $activity->save();

        $html = view('Template::user.project.single_message', ['activity' => $activity])->render();
        return response()->json([
            'success' => true,
            'activity' => $activity,
            'html' => $html,
        ]);
    }

    public function deleteHistory($id)
    {
        $projectActivity =   ProjectActivity::where('user_id', auth()->id())->findOrFail($id);
        $path = getFilePath('files');
        if ($projectActivity->files) {
            foreach ($projectActivity->files as $item) {
@unlink($path . '/' . $item);
            }
        }
        $projectActivity->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function acceptProject(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'deadline' => 'required|date|after_or_equal:tomorrow|date_format:Y-m-d',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        }

        $seller  = auth()->user();
        $project = Project::pending()->where('id', $id)->where('seller_id',  $seller->id)->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ]);
        }

        $order   = Order::pending()->with('gig', 'user')->find($project->order_id);
        $buyer   = $order->user;

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ]);
        }

        if ($buyer->balance <  $order->price) {
            notify($buyer, 'INSUFFICIENT_BALANCE', [
                'title'  => $order->gig->title,
                'amount' => showAmount($order->price) . ' ' . gs('cur_text'),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Buyer balance isn\'t enough for accept this order',
            ]);
        }

        $project->status = Status::PROJECT_ACCEPTED;
        $project->save();

        $order->status   = Status::ORDER_ACCEPTED;
        $order->deadline = Carbon::parse($request->deadline);
        $order->save();

        $general = gs();
        $buyerOrderFee = ($order->price * $general->buyer_percent_fee / 100) + $general->buyer_fixed_fee;

        $buyer->balance -= $order->price;
        $buyer->save();

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->user_id    = $seller->id;
        $activity->message    = 'Order request accepted';
        $activity->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $buyer->id;
        $transaction->amount       = $order->price;
        $transaction->post_balance = $buyer->balance;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Order accepted for ' . $order->number;
        $transaction->trx          = $order->number;
        $transaction->remark       = 'accepted_order';
        $transaction->save();

        if ($buyerOrderFee) {
            $buyer->balance -= $buyerOrderFee;
            $buyer->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $buyer->id;
            $transaction->amount       = $buyerOrderFee;
            $transaction->post_balance = $buyer->balance;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Buyer fee of order accepted for ' . $order->number;
            $transaction->trx          = $order->number;
            $transaction->remark       = 'buyer_order_fee';
            $transaction->save();
        }

        notify($buyer, 'ORDER_ACCEPTED', [
            'title'    => $order->gig->title,
            'amount'   => showAmount($order->price) . ' ' .  __($general->cur_text),
            'deadline' => showDateTime($order->deadline),
            'seller'   => $seller->fullname
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order accepted successfully',
        ]);
    }

    public function rejectProject($id)
    {
        $seller  = auth()->user();
        $project = Project::pending()->where('id', $id)->where('seller_id',  $seller->id)->first();
        if (!$project) {
            $notify[] = ['error', 'Project not found'];
            return back()->withNotify($notify);
        }

        $order   = Order::pending()->with('gig', 'user')->find($project->order_id);
        $buyer   = $order->user;

        if ($seller->id != $order->gig->user_id) {
            $notify[] = ['error', 'This is invalid action!'];
            return back()->withNotify($notify);
        }

        $project->status = Status::PROJECT_REJECTED;
        $project->save();

        $order->status   = Status::ORDER_REJECTED;
        $order->save();

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->user_id    = $seller->id;
        $activity->message    = 'Order request rejected';
        $activity->save();

        notify($buyer, 'ORDER_REJECTED', [
            'title'  => $order->gig->title,
            'seller' => $seller->fullname,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order rejected successfully',
        ]);
    }


    public function completeProject(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        }

        $buyer =  auth()->user();
        $project = Project::accepted()->where('id', $id)->where('buyer_id',  $buyer->id)->first();
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ]);
        }
        $order   = Order::accepted()->with('gig')->find($project->order_id);
        $seller =  $order->gig->user;


        $project->status = Status::PROJECT_COMPLETED;
        $project->save();

        $order->status   = Status::ORDER_COMPLETED;
        $order->save();


        $general        = gs();
        $sellerOrderFee = ($order->price * $general->seller_percent_fee / 100) + $general->seller_fixed_fee;


        $seller->balance +=  $order->price;
        $seller->save();

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->user_id    = $buyer->id;
        $activity->message    = 'Order completed with review and rating';
        $activity->save();

        $transaction = new Transaction();
        $transaction->user_id      = $seller->id;
        $transaction->amount       = $order->price;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Order completed for  ' . $order->number;
        $transaction->trx          = $order->number;
        $transaction->remark       = 'completed_order';
        $transaction->save();

        if ($sellerOrderFee) {
            $seller->balance -= $sellerOrderFee;
            $seller->save();

            $transaction = new Transaction();
            $transaction->user_id      = $seller->id;
            $transaction->amount       = $sellerOrderFee;
            $transaction->post_balance = $seller->balance;
            $transaction->trx_type     = '-';
            $transaction->details      = "'Seller fee' of order completed no " . $order->number;
            $transaction->trx          = $order->number;
            $transaction->remark       = 'seller_order_fee';
            $transaction->save();
        }

        $buyer = $order->user;

        notify($seller, 'ORDER_COMPLETED', [
            'title'  => $order->gig->title,
            'amount' => showAmount($order->price, currencyFormat:false) . ' ' . __($general->cur_text),
            'buyer'  => $buyer->fullname
        ]);

        //review
        $gig = $order->gig;
        $review = new Review();
        $review->user_id    = $buyer->id;
        $review->gig_id     = $gig->id;
        $review->project_id = $project->id;
        $review->rating     = $request->rating;
        $review->review     = $request->review;
        $review->save();

        $reviews = Review::where('gig_id', $gig->id)->get(['rating']);

        $gig->avg_rating = $reviews->sum('rating') / $reviews->count();
        $gig->save();

        return response()->json([
            'success' => true,
            'message' => 'Order completed successfully',
        ]);
    }

    public function reviewRatingUpdate(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        }

        $buyer =  auth()->user();
        $project = Project::completed()->where('id', $id)->where('buyer_id',  $buyer->id)->first();
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ]);
        }

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->user_id    = $buyer->id;
        $activity->message    = 'UPDATED: Project review and ' . $request->rating . ' star rating';
        $activity->save();

        $review = Review::where('project_id', $project->id)->where('user_id', $buyer->id)->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Project review not found',
            ]);
        }
        $review->rating     = $request->rating;
        $review->review     = $request->review;
        $review->save();

        $gig =  $project->gig;
        $reviews = Review::where('gig_id', $gig->id)->get(['rating']);
        $gig->avg_rating = $reviews->sum('rating') / $reviews->count();
        $gig->save();

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
        ]);
    }

    public function reportProject(Request $request, $id)
    {
        $validation  = Validator::make($request->all(), [
            'message'    => 'required|string',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }

        $userId  = auth()->id();
        $project = Project::accepted()
            ->where('id', $id)->where(function ($query) use ($userId) {
                $query->orWhere('seller_id', $userId)->orWhere('buyer_id', $userId);
            })->first();
        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ]);
        }
        $order  = Order::accepted()->find($project->order_id);
        $seller = $order->gig->user;
        $buyer  = $project->buyer;

        $project->status = Status::PROJECT_REPORTED;
        $project->save();

        $order->status   = Status::ORDER_REPORTED;
        $order->save();

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->user_id    = $project->seller_id == $userId ? $seller->id : $buyer->id;
        $activity->message    = "REPORTED: “" . $request->message . "”";
        $activity->save();

        if ($project->seller_id == $userId) {
            notify($buyer, 'ORDER_REPORTED', [
                'title'    => $order->gig->title,
                'reporter' => $buyer->fullname,
            ]);
        } else {
            notify($seller, 'ORDER_REPORTED', [
                'title'    => $order->gig->title,
                'reporter' => $buyer->fullname,
            ]);
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $buyer->id;
        $adminNotification->title     = 'A new report has been submitted by ' . $project->seller_id == $userId ? $seller->fullname : $buyer->fullname;
        $adminNotification->click_url = urlPath('admin.project.details', $project->id);
        $adminNotification->save();

        return response()->json([
            'success' => true,
            'message' => 'Order reported successfully',
        ]);
    }
}
