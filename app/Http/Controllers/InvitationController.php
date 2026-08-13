<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;

class InvitationController extends Controller
{
    // SEND INVITATION
    public function send(Request $request)
    {
        Invitation::create([
            'team_id' => $request->team_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Invitation sent successfully!');
    }

    // ACCEPT
    public function accept($id)
    {
        $inv = Invitation::find($id);
        $inv->status = 'accepted';
        $inv->save();

        return back()->with('success', 'Invitation accepted!');
    }

    // REJECT
    public function reject($id)
    {
        $inv = Invitation::find($id);
        $inv->status = 'rejected';
        $inv->save();

        return back()->with('success', 'Invitation rejected!');
    }
}