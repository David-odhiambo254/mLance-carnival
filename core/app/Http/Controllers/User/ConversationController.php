<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    public function index()
    {
        $pageTitle = 'Conversations';
        $user   = auth()->user();
        $conversationList = Conversation::where(function ($query) use ($user) {
            $query->where('seller_id', $user->id)->orWhere('buyer_id', $user->id);
        })->with('messages', 'messages.sender')->distinct()->get();

        return view('Template::user.conversation.index', compact('pageTitle', 'conversationList', 'user'));
    }
    public function details($id)
    {
        $pageTitle = 'Conversation Details';
        $user      = auth()->user();

        $conversationList = Conversation::where(function ($query) use ($user) {
            $query->where('seller_id', $user->id)->orWhere('buyer_id', $user->id);
        })->with('messages', 'messages.sender')->distinct()->orderBy('id','desc')->get();

        $conversationMessages = Conversation::where(function ($query) use ($user) {
            $query->where('seller_id', $user->id)->orWhere('buyer_id', $user->id);
        })->with('messages', 'messages.sender')->find($id);

        if (!$conversationMessages) {
            $notify[] = ['error', 'Invalid action!'];
            return back()->withNotify($notify);
        }

        $unreadMessages =  $conversationMessages->messages->where('sender_id', $user->id)->where('read', Status::NO);

        ConversationMessage::where('conversation_id',$conversationMessages->id)->where('receiver_id',$user->id)->where('read', Status::NO)->update(['read' =>  Status::YES]);

        return view('Template::user.conversation.details', compact('pageTitle', 'conversationList', 'conversationMessages', 'user'));
    }

    public function messages(Request $request)
    {

        $request->validate([
            'message'   => 'required|min:5',
            'seller_id' => 'required',
        ]);

        $buyer  = auth()->user();
        $seller = User::findOrFail($request->seller_id);

        if (!$seller) {
            $notify[] = ['success', 'Seller is not existing.'];
            return back()->withNotify($notify);
        }

        $conversation=Conversation::where(function($q) use($seller,$buyer){
            $q->where('seller_id',$seller->id)->where('buyer_id',$buyer->id);
        })
        ->orWhere(function($q) use($seller,$buyer){
            $q->where('seller_id',$buyer->id)->where('buyer_id',$seller->id);
        })->first();


        if (!$conversation) {
            $conversation            = new Conversation();
            $conversation->seller_id = $seller->id;
            $conversation->buyer_id  = $buyer->id;
            $conversation->gig_id    = $request->gig_id ?? 0;
            $conversation->save();
        }


        $message                  = new ConversationMessage();
        $message->conversation_id = $conversation->id;
        $message->sender_id       = auth()->id();
        $message->receiver_id     = $seller->id;
        $message->message         = $request->message;
        $message->save();

        return to_route('user.conversation.details', $conversation->id);
    }

    public function messaging(Request $request, $id)
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

        $conversation = Conversation::find($id);
        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid conversation',
            ]);
        }

        $user = auth()->user();

        $message                  = new ConversationMessage();
        $message->conversation_id = $conversation->id;
        $message->sender_id       = auth()->id();

        if($conversation->buyer_id==auth()->id()){
            $receiverId = $conversation->seller_id;
        }else{
            $receiverId = $conversation->buyer_id;
        }

        $message->receiver_id = $receiverId;
        $message->message     = $request->message;
        $message->save();

        $html = view('Template::user.conversation.message', ['message' => $message, 'user' => $user])->render();
        return response()->json([
            'success' => true,
            'message' => $message,
            'html'    => $html,
        ]);
    }


    public function search(Request $request)
    {
        $search = $request->search;
        $userId = auth()->user()->id;
        $conversationList = Conversation::searchable(['messages.sender:username,firstname,lastname'])->where(function ($query) use ($userId) {
            $query->where('seller_id', $userId)->orWhere('buyer_id', $userId);
        })->with('messages', 'messages.sender')->distinct()->get();

        $html = view('Template::user.conversation.single_conversation', compact('conversationList'))->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
            'conversationList' =>  $conversationList->count()
        ]);
    }
}
