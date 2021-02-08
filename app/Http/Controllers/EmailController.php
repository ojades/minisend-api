<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;

class EmailController extends Controller
{
    public function queue(Request $request)
    {
        $data = $request->all();
        $transaction = Transactions::create($data);
        $file = $request->file('files');
        Log::info($data['sender_email']);
        Log::info(json_encode($data));

        $mail = new PHPMailer();
        $mail->setFrom($data['sender_email'], $data['sender_name']);
        $mail->addAddress($data['']);



        return response()->json($transaction, 200);
    }
}
