<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\Project;

class ChatController extends Controller
{
    //

    public function index($receiverId)
    {
        $authUser = Auth::user();
        $receiver = User::findOrFail($receiverId);

        $messages = Message::where(function($q) use ($authUser, $receiverId) {
            $q->where('sender_id', $authUser->id)
              ->where('receiver_id', $receiverId);
        })->orWhere(function($q) use ($authUser, $receiverId) {
            $q->where('sender_id', $receiverId)
              ->where('receiver_id', $authUser->id);
        })->orderBy('created_at', 'asc')->get();

        if (!Project::whereHas('applicants', function ($q) use ($receiverId) {
            $q->where('freelancer_id', $receiverId);
        })->exists()) {
            abort(403); // tidak boleh chat
        }

        return view('chat.index', compact('receiver', 'messages'));
    }

    public function store(Request $request, $receiverId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        return back();
    }
}
