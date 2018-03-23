<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Auth, DB;

class MessageController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $status = $request->status;

        $list = Message::where('user_id', $request->user)
            ->when($status != 0, function ($query) use ($status){
                $query->where('status', $status);
            })->paginate(20);

        return response()->json(responseFormat(1, 'success', $list));
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        Message::where('user_id', $request->user)->where('id', $request->id)->update([
            'status' => 2
        ]);
    }
}