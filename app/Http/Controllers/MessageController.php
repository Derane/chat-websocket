<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

    }
}
