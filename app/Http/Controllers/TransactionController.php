<?php


namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Constants\Responses;
use App\Jobs\EmailTransactionJob;
use App\Models\Templates;
use App\Models\Transactions;
use Cache;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'recipient_name' => 'required|string',
            'recipient_email' => 'required|email',
            'subject' => 'required|string',
            'template' => 'required|string|exists:App\Models\Templates,slug',
            'data' => 'json'
        ]);

        $template = Templates::getBySlug($data['template']);

        if(!$template) {
            return $this->sendError(
                sprintf(Responses::RESOURCE_DOES_NOT_EXISTS[0], 'template'),
                Responses::RESOURCE_DOES_NOT_EXISTS[1],
                404
            );
        }

        $data['sender_name'] = $template->sender_name;
        $data['sender_email'] = $template->sender_email;

        $transaction = Transactions::create($data);

        EmailTransactionJob::dispatch($transaction, $data, stripslashes($template->content));

        return $this->sendSuccess(['message' => 'Queued successfully']);
    }

    public function getAll(Request $request)
    {
        $data = $request->validate([
            'sender' => 'string',
            'recipient' => 'string',
            'subject' => 'string',
        ]);

        $transactions = Transactions::filter($data);
        return $this->sendSuccess($transactions);
    }

    public function getDetails(Request $request, $id)
    {
        $transaction = Cache::rememberForever(Constants::METRICS, function ($id) {
            return  Transactions::getById($id);;
        });

        return $this->sendSuccess($transaction);
    }

    public function getMetrics(Request $request)
    {
        return $this->sendSuccess($metrics);
    }
}
