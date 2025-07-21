<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\Project;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Validator;

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

        // Validasi arah hubungan Hired
        $valid = Project::whereHas('applicants', function ($q) use ($authUser, $receiverId) {
            $q->where('status', 'Hired')
            ->where(function ($query) use ($authUser, $receiverId) {
                $query->where(function ($inner) use ($authUser, $receiverId) {
                    // Freelancer sedang login, ingin chat dengan klien
                    $inner->where('freelancer_id', $authUser->id)
                            ->whereHas('project', function ($p) use ($receiverId) {
                                $p->where('client_id', $receiverId);
                            });
                })->orWhere(function ($inner) use ($authUser, $receiverId) {
                    // Klien sedang login, ingin chat dengan freelancer
                    $inner->where('freelancer_id', $receiverId)
                            ->whereHas('project', function ($p) use ($authUser) {
                                $p->where('client_id', $authUser->id);
                            });
                });
            });
        })->exists();

        if (!$valid) {
            abort(403); // Tidak punya hubungan kerja yang valid
        }


        return view('chat.index', compact('receiver', 'messages'));
    }

    public function store(Request $request, $userId)
    {

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $userId,
            'message' => $request->message,
        ]);
       event(new MessageSent($message));


        return redirect()->back();
    }
}
