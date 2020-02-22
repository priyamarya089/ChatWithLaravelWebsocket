<?php

namespace App\Http\Controllers;

use App\Events\messageEvent;
use App\message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class chatboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('chat');
    }

    function fetchMessages($id)
    {
        return message::with('user')->where([ ['from',$id] , ['to',auth()->id()] ])->orWhere([ ['from',auth()->id()] , ['to',$id] ])->get();
    }

    public function sendMessages(Request $request)
    {
        $event = false;
        $message = auth()->user()->usermessage()->create([
            'from' => auth()->id(),
            'to' => $request->to,
            'message' => $request->message,
            ]);

            // $this->message = "Hello";
            // $event = event(new \App\Events\messageEvent($message) );
            broadcast(new messageEvent( $message ))->toOthers();

        // return $message;
        // Log::info($event);
        return ['status' => 'success'];
    }

    // function show()
    // {
    //     return view('sender');
    // }

    // function send()
    // {
    //     $message = request()->message;
    //     return event(new message($message));
    // }
}
