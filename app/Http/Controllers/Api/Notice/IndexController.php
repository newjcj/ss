<?php

namespace App\Http\Controllers\Api\Notice;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Auth, DB;

class IndexController extends Controller
{
    public function status()
    {
        $notice = Notice::where('id', 1)->first();

        return response()->json(responseFormat($notice->content ? 1 : 0, 'success'));
    }

    public function content()
    {
        $notice = Notice::where('id', 1)->first();

        return response()->json(responseFormat(1, 'success', $notice->content));
    }

}

