<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Goods;

/**
 * Class GoodsController
 * @package App\Http\Controllers
 */
class GoodsController extends Controller
{

    public function show($id)
    {
        $goods = Goods::find($id);
        if ($goods) {
            return view('frontend.goods.show', compact('goods'));
        }
        abort(404);
    }
}