<?php

namespace App\Http\Controllers;

use App\Constants\Responses;
use App\Models\MailList;
use Illuminate\Http\Request;

class MailListController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:App\Models\MailList,email',
            'mail_list_group_id' => 'required|integer',
            'status' => 'string|in:enabled,disabled'
        ]);

        $list = MailList::create($data);

        return $this->sendSuccess($list);
    }

    public function edit(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'string',
            'email' => 'email',
            'mail_list_group_id' => 'integer',
            'status' => 'string|in:enabled,disabled'
        ]);

        $contact = MailList::where('id', $id)->first();

        if(!$contact) {
            $this->sendError(
                sprintf(Responses::RESOURCE_DOES_NOT_EXISTS[0], 'entry'),
                Responses::RESOURCE_DOES_NOT_EXISTS[1],
                404
            );
        }

        $contact->update($data);

        return $this->sendSuccess($contact->fresh());

    }

    public function delete(Request $request, $id)
    {
        $contact = MailList::where('id', $id)->first();

        if(!$contact) {
            $this->sendError(
                sprintf(Responses::RESOURCE_DOES_NOT_EXISTS[0], 'entry'),
                Responses::RESOURCE_DOES_NOT_EXISTS[1],
                404
            );
        }

        $contact->delete();

        return $this->sendSuccess([]);
    }

    public function filter(Request $request)
    {
        $data = $request->validate([
            'name' => 'string',
            'email' => 'email',
            'mail_list_group' => 'string',
            'status' => 'string|in:enabled,disabled'
        ]);

        $list = MailList::filter($data);

        return $this->sendSuccess($list);
    }
}
