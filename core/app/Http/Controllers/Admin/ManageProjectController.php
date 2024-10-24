<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ManageProjectController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Projects';
        $projects  = $this->projectData();
        return view('admin.project.index', compact('pageTitle', 'projects'));
    }
    public function reported()
    {
        $pageTitle = "Reported Projects";
        $projects = $this->projectData('reported');
        return view('admin.project.index', compact('pageTitle', 'projects'));
    }
    public function pending()
    {
        $pageTitle = "Pending Projects";
        $projects = $this->projectData('pending');
        return view('admin.project.index', compact('pageTitle', 'projects'));
    }
    public function accepted()
    {
        $pageTitle = "Accepted Projects";
        $projects = $this->projectData('accepted');
        return view('admin.project.index', compact('pageTitle', 'projects'));
    }
    public function rejected()
    {
        $pageTitle = "Rejected Projects";
        $projects = $this->projectData('rejected');
        return view('admin.project.index', compact('pageTitle', 'projects'));
    }

    public function completed()
    {
        $pageTitle = "Completed Projects";
        $projects = $this->projectData('completed');
        return view('admin.project.index', compact('pageTitle', 'projects'));
    }

    protected function projectData($scope = null)
    {
        if ($scope) {
            $projects = Project::$scope();
        } else {
            $projects = Project::query();
        }

        $projects = $projects->searchable(['gig:title', 'buyer:username', 'seller:username'])->with('gig', 'order', 'buyer', 'seller')->orderBy('id', 'DESC');
        if (request()->date) {
            try {
                $date      = explode('-', request()->date);
                $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
                $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
            } catch (\Exception $e) {
                throw ValidationException::withMessages(['error' => 'Unauthorized action']);
            }
            request()->merge(['start_date' => $startDate, 'end_date' => $endDate]);
            $projects =  $projects->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereDate('deadline', '>=', $startDate)->whereDate('deadline', '<=', $endDate);
            });
        }
        return $projects->paginate(getPaginate());
    }

    public function details($id)
    {
        $pageTitle = "Project Details";
        $project   = Project::with('gig', 'order', 'seller', 'buyer', 'projectActivities.user', 'projectActivities.admin')->findOrFail($id);
        return view('admin.project.details', compact('pageTitle', 'project'));
    }

    public function message(Request $request, $id)
    {
        $validation  = Validator::make($request->all(), [
            'message'  => 'required',
        ]);

        $project = Project::find($id);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }
        $admin = auth()->guard('admin')->user();
        $activity = new ProjectActivity();
        $activity->project_id = $id;
        $activity->admin_id =  $admin->id;
        $activity->message = $request->message;
        $activity->save();

        $html = view('admin.project.single_message', ['activity' => $activity, 'project' => $project])->render();
        return response()->json([
            'success' => true,
            'activity' => $activity,
            'html' => $html,
        ]);
    }


    public function deleteHistory($id)
    {
        $admin = auth()->guard('admin')->user();

        $projectActivity =   ProjectActivity::where('admin_id',  $admin->id)->findOrFail($id);

        $path = getFilePath('files');
        if ($projectActivity->files) {
            foreach ($projectActivity->files as $item) {
                @unlink($path . '/' . $item);
            }
        }
        $projectActivity->delete();
        $notify[] = ['success', 'Deleted successfully'];
        return back()->withNotify($notify);
    }

    public function files($id)
    {
        $pageTitle = "Project Files";
        $project   = Project::with('gig', 'order', 'seller', 'buyer', 'projectActivities.user')->findOrFail($id);
        return view('admin.project.files', compact('pageTitle', 'project'));
    }

    public function uploadFiles(Request $request, $id)
    {
        $validation  = Validator::make($request->all(), [
            'files'             => ['required', 'array'],
            'files*'            => ['file', new FileTypeValidate(['jpg', 'jpeg', 'png', 'zip', 'rar', 'pdf', '3gp', 'mpeg3', 'x-mpeg-3', 'mp4', 'mpeg', 'mpkg', 'doc', 'docx', 'gif', 'txt', 'svg', 'wav', 'xls', 'xlsx', '7z'])],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }
        $path = getFilePath('files');
        $admin = auth()->guard('admin')->user();

        $activity = new ProjectActivity();
        $activity->project_id = $id;

        $allFiles = [];

        foreach ($request->file('files') as $file) {
            try {
                $directory = date("Y") . "/" . date("m") . "/" . date("d");
                $path = getFilePath('files') . '/' . $directory;
                $allFiles[] =  $directory . '/' . fileUploader($file, $path);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'File could not upload'];
                return $notify;
            }
        }

        $activity->files = $allFiles;
        $activity->admin_id =  $admin->id;
        $activity->save();

        return response()->json([
            'success'  => true,
            'activity' => $activity,
        ]);
    }

    public function downloadFile($id, $file)
    {
        $project = Project::where('id', $id)->with('gig')->first();

        if (!$project) {
            $notify[] = ['error', 'Project not found'];
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


    public function rejectProject($id)
    {
        $project = Project::reported()->where('id', $id)->first();
        if (!$project) {
            $notify[] = ['error', 'Project not found'];
            return back()->withNotify($notify);
        }
        $seller  = $project->seller;
        $buyer   = $project->buyer;
        $order   = Order::reported()->with('gig', 'user')->find($project->order_id);

        $general = gs();
        $buyerOrderFee = ($order->price * $general->buyer_percent_fee / 100) + $general->buyer_fixed_fee;

        $buyer->balance += $order->price;
        $buyer->save();

        $project->status = Status::PROJECT_REJECTED;
        $project->save();

        $order->status   = Status::ORDER_REJECTED;
        $order->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $buyer->id;
        $transaction->amount       = $order->price;
        $transaction->post_balance = $buyer->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Order refunded for ' . $order->number;
        $transaction->trx          = $order->number;
        $transaction->remark       = 'rejected_order';
        $transaction->save();

        if ($buyerOrderFee) {
            $buyer->balance += $buyerOrderFee;
            $buyer->save();
            $transaction               = new Transaction();
            $transaction->user_id      = $buyer->id;
            $transaction->amount       = $buyerOrderFee;
            $transaction->post_balance = $buyer->balance;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Order fee refunded for ' . $order->number;
            $transaction->trx          = $order->number;
            $transaction->remark       = 'rejected_order_fee';
            $transaction->save();
        }

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->admin_id   = auth()->guard('admin')->user()->id;
        $activity->message    = 'Order rejected by authority';
        $activity->save();


        notify($buyer, 'REPORTED_ORDER_REJECTED', [
            'title'  => $order->gig->title,
            'amount' => showAmount($order->price, currencyFormat:false) . ' ' . gs('cur_text'),
        ]);

        notify($seller, 'REPORTED_ORDER_REJECTED', [
            'title'  => $order->gig->title,
            'amount' => showAmount($order->price, currencyFormat:false) . ' ' . gs('cur_text'),
        ]);

        $notify[] = ['success', 'Project rejected successfully'];
        return back()->withNotify($notify);
    }


    public function completeProject($id)
    {
        $project = Project::reported()->where('id', $id)->first();
        if (!$project) {
            $notify[] = ['error', 'Project not found'];
            return back()->withNotify($notify);
        }
        $buyer   = $project->buyer;
        $seller  = $project->seller;
        $order   = Order::reported()->with('gig', 'user')->find($project->order_id);

        $general        = gs();
        $sellerOrderFee = ($order->price * $general->seller_percent_fee / 100) + $general->seller_fixed_fee;

        $seller->balance += $order->price;
        $seller->save();

        $project->status = Status::PROJECT_COMPLETED;
        $project->save();

        $order->status   = Status::ORDER_COMPLETED;
        $order->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $seller->id;
        $transaction->amount       = $order->price;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Order accepted for ' . $order->number;
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

        $activity = new ProjectActivity();
        $activity->project_id = $project->id;
        $activity->admin_id   = auth()->guard('admin')->user()->id;
        $activity->message    = 'Order completed by authority';
        $activity->save();

        notify($buyer, 'REPORTED_ORDER_COMPLETED', [
            'title'  => $order->gig->title,
            'seller' => $seller->fullname,
            'amount' => showAmount($order->price, currencyFormat:false) . ' ' . gs('cur_text'),
        ]);

        notify($seller, 'REPORTED_ORDER_COMPLETED', [
            'title'  => $order->gig->title,
            'buyer'  => $buyer->fullname,
            'amount' => showAmount($order->price, currencyFormat:false) . ' ' . gs('cur_text'),
        ]);

        $notify[] = ['success', 'Project completed successfully'];
        return back()->withNotify($notify);
    }
}
