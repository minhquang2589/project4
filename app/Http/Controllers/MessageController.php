<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Images;
use App\Models\Discounts;
use App\Models\Cates;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use App\Models\Details;
use App\Models\ProductVariants;
use App\Models\Sizes;
use App\Models\Colors;
use App\Models\Message;
use App\Models\UserInfor;
use App\Models\User;
use App\Models\LikedProducts;
use Exception;

class MessageController extends Controller
{
    //
    public function sendMessage(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'content' => 'max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->all(),
            ]);
        }
        $user = Auth::user();

        if ($user->id != $request->sender_id || $user->id == $request->receiver_id) {
            return response()->json([
                'success' => false,
                'error' => ['error'],
            ]);
        }


        $message = new Message();
        $message->sender_id = $user->id;
        $message->receiver_id = $request->receiver_id;
        $message->content = $request->content;
        $message->status = 'sent';
        $message->save();
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
    }
    ///
    public function getChat()
    {
        try {
            $user = Auth::user();
            $messages = Message::select('sender_id', 'receiver_id')
                ->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            $usersInfo = [];
            foreach ($messages as $message) {
                $senderId = $message->sender_id;
                $receiverId = $message->receiver_id;
                if ($senderId != $user->id && !isset($usersInfo[$senderId])) {
                    $sender = User::find($senderId);
                    $lastMessage = Message::where('sender_id', $senderId)
                        ->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id)
                        ->where('receiver_id', $senderId)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $usersInfo[$senderId] = [
                        'id' => $sender->id,
                        'name' => $sender->name,
                        'last_time' => $lastMessage->updated_at,
                        'image' => $sender->image,
                        'last_message' => $lastMessage ? $lastMessage->content : ''
                    ];
                }
                if ($receiverId != $user->id && !isset($usersInfo[$receiverId])) {
                    $receiver = User::find($receiverId);
                    $lastMessage = Message::where('sender_id', $receiverId)
                        ->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id)
                        ->where('receiver_id', $receiverId)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $usersInfo[$receiverId] = [
                        'id' => $receiver->id,
                        'name' => $receiver->name,
                        'image' => $receiver->image,
                        'last_time' => $lastMessage->updated_at,
                        'last_message' => $lastMessage ? $lastMessage->content : ''
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'chat' =>   $usersInfo,
                'chatQuantity' =>   count($usersInfo),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
    ///
    public function getUserInfor($id)
    {
        try {
            $user = Auth::user();
            $receiverInfor = User::where('id', $id)->first();
            $senderMessages = DB::table('message')
                ->select(
                    'message.content',
                    'message.sender_id',
                    'message.receiver_id',
                    'message.created_at as datetime',
                    'users.image'
                )
                ->join('users', 'message.sender_id', '=', 'users.id')
                ->where('message.sender_id', $user->id)
                ->where('message.receiver_id', $receiverInfor->id)
                ->orderBy('message.created_at', 'desc')
                ->get();

            $receiverMessages = DB::table('message')
                ->select(
                    'message.content',
                    'message.sender_id',
                    'message.receiver_id',
                    'message.created_at as datetime',
                    'users.image'
                )
                ->join('users', 'message.sender_id', '=', 'users.id')
                ->where('message.receiver_id', $user->id)
                ->where('message.sender_id', $receiverInfor->id)
                ->orderBy('message.created_at', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'receiverInfor' =>   $receiverInfor,
                'senderMessages' =>   $senderMessages,
                'receiverMessages' =>   $receiverMessages,
                'id' =>   $id,
                'user_id' =>   $user->id,

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
    }
}
