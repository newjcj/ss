<?php

namespace App\Http\Controllers\Backend\System;

use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class NoticeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notice = Notice::where('id', 1)->first();
        return view('backend.system.notice.index', compact('notice'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        Notice::where('id', 1)->update([
            'content' => $request->contents
        ]);
        return back();
    }
}
