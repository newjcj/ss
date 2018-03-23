<?php

namespace App\Http\Controllers\Api\Goods;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Class IndexController
 * @package App\Http\Controllers\Goods
 */
class IndexController extends Controller
{
    /**
     * 商品列表
     * $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $goods = Goods::where("status",1)->whereNull('vocational')->orderBy('sort', 'asc')->orderBy("id", "asc")->paginate(10);

        $goodsList = [];
        foreach ($goods as $item) {
            $goodsList[] = [
              'id' => $item->id,
              'title' => $item->title,
              'price' => $item->price,
              'sale_quantity' => $item->sale_quantity,
              'image' => !empty($item->images) ? $item->images[0]: '',
            ];
        }

        return response()->json(responseFormat(1, 'success', $goodsList));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        $goodsInfo = Goods::select('id', 'title', 'desc', 'price', 'sale_quantity', 'images')
            ->where('id', $request->id)
            ->first()
            ->toArray();
        $goodsImage = [];
        if ($goodsInfo['images']) {
            foreach ($goodsInfo['images'] as $item) {
                $goodsImage[] = 'http://' . $request->getHost() . $item;
            }
        }
        $goodsInfo['images'] = $goodsImage;

        return response()->json(responseFormat(1, 'success', $goodsInfo));
    }
    //取一级分类列表
    public function categoryOne(Request $request)
    {
        $categorys = Category::where('pid',1)->whereNotIn('name',['飕飕商城'])->get();
        r(1,'','',$categorys);
    }
    //取一级分类下的所有二级分类和商品
    public function categoryTwo(Request $request)
    {
        $categorys = Category::where('pid',$request->input('id'))->whereNotIn('name',['飕飕商城'])->get();
        $re=[];
        foreach ($categorys as $k=>$category) {
            $re[$k]['category']=Category::find($category->id);
            $goodss=Goods::where('category_id',$category->id)->offset(0)->limit(6)->get();
            $re[$k]['data']=$goodss;
        }
        r(1, '获取成功', '', $re);
    }
    //取推荐商品
    public function recommended(Request $request)
    {
        $count =count(Goods::all());
        $start = rand(1,$count-10);
        $re=[];
        $ure = function () use ($start) {
            $dataone =DB::select('select * from goods where vocational != NULL limit ?, 10',[$start]);
            array_walk($dataone,function($v){
                $v->images = explode(',',$v->images);
            });
            return $dataone;
        };
        $re['dataOne'] =$ure();
        $re['dataTwo'] =$ure();
        r(1, '获取成功', '', $re);
    }
    //取4个会员专区商品
//    public function ()
//    {
//
//    }
    //取商品属性
    public function attributeGet(Request $request)
    {
        $goodsid = $request->input('id');
        $data=[];
        foreach (Goods::find($goodsid)->attributes as $attribute) {
            foreach (Attribute::find($attribute->id)->attributelists as $k=>$attributelist) {
                $data[$attribute->name][$k]['id']=$attributelist->id;
                $data[$attribute->name][$k]['name']=$attributelist->name;
            }
        }
        return $data;

    }

}
