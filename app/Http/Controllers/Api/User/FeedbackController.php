<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Message;
use Illuminate\Http\Request;
use Auth, DB;

class FeedbackController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $list = Feedback::where('user_id', $request->user)->with('reply')->paginate(20);

        return response()->json(responseFormat(1, 'success', $list));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $exist = Feedback::where('content', $request->contents)->where('user_id', $request->user)->first();

        if ($exist) {
            return response()->json(responseFormat(0, '我们已经收到您的反馈，请不要重复提交'));
        }

        Feedback::create([
            'user_id' => $request->user,
            'content' => $request->contents,
        ]);
        return response()->json(responseFormat(1, '我们已经收到您的反馈，工作人员会尽快回复您'));
    }
}