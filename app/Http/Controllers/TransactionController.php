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
            'sender' => 'string|nullable',
            'recipient' => 'string|nullable',
            'subject' => 'string|nullable',
            'limit' => 'integer|nullable',
            'status' => 'string|nullable'
        ]);

        $limit = !empty($data['limit']) ? $data['limit'] : 10;

        $transactions = Transactions::filter($data, $limit);

        $statuses = Cache::rememberForever(Constants::STATUSES, function () {
            $result = Transactions::select('status')->distinct()->get()->toArray();
            return array_column($result, 'status');
        });

        return $this->sendSuccess($transactions, ['statuses' => $statuses]);
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
        $metrics = Transactions::getMetrics();
        return $this->sendSuccess($metrics);
    }
}
