<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Owner;
use App\Mail\InfoMail;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;

class AdminController extends Controller
{
    public function index()
    {
        $owners = Owner::all();
        return view('admin/admin', compact('owners'));
    }

    public function store(RegisterRequest $request)
    {
        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return redirect()->route('admin.home')->with('message', '店舗代表者を登録しました。');
    }

    public function sendEmail(SendEmailRequest $request)
    {
        $id = $request->name;
        if ($id==='all') {
            $recipients = Owner::all();
        } else {
            $owner = Owner::find($id);
            $recipients = [
                ['email' => $owner->email, 'name' => $owner->name],
            ];
        }

        foreach ($recipients as $recipient) {
            $data = [
                'name' => $recipient['name'],
                'email' => $recipient['email'],
                'message' => $request->message
            ];

            Mail::to($recipient['email'])->send(new InfoMail($data));
        }

        return back()->with('message', 'メールを送信しました。');
    }
}
