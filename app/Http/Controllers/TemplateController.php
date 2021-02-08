<?php

namespace App\Http\Controllers;

use App\Constants\Responses;
use App\Models\Templates;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();
        $slug = str_replace(' ', '-', strtolower($data['name']));

        $template = Templates::where('slug', $slug)->first();

        if($template) {
            return $this->sendError(Responses::TEMPLATE_EXISTS[0], Responses::TEMPLATE_EXISTS[1], 400);
        }

        $data['content'] = addslashes($data['content']);
        $data['slug'] = $slug;
        $template = Templates::create($data);

        return $this->sendSuccess($template['name']);
    }

    public function edit(Request $request, $id): JsonResponse
    {
        $data = $request->all();

        $template = Templates::where('id', $id)->first();

        if(!$template) {
            return $this->sendError(
                sprintf(Responses::RESOURCE_DOES_NOT_EXISTS[0], 'template'),
                Responses::RESOURCE_DOES_NOT_EXISTS[1],
                404
            );
        }

        $template->edit($data);

        return $this->sendSuccess($template);
    }
}
