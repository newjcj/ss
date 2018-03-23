<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
/**
 * Class GoodsController
 * @package App\Http\Controllers\Backend
 */
class GoodsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('backend.goods.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backend.goods.create',[
            'category'=>new Category(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $ab=$request->input('attributedata');
        $request = $request->except('_token');
        $request['images']=implode(',',array_filter($request['images']));
        $re = Goods::create($request);
        Goods::abute($ab,$re->id,[]);
        return  back();
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('backend.goods.edit', [
            'goods'=>Goods::find($id),
            'category'=>new Category(),
        ]);
    }
    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
//        print_r($request->images);exit;
        $ab=$request->input('attributedata');
        Goods::where('id', $request->id)->update([
            'title' => $request->title,
            'telephone_fare' => $request->telephone_fare,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'carriage' => $request->carriage,
            'stock_quantity'=> $request->stock_quantity,
            'sale_quantity' => $request->sale_quantity,
            'seller_one_integral'=> $request->seller_one_integral,
            'seller_two_integral'=> $request->seller_two_integral,
            'seller_three_integral' => $request->seller_three_integral,
            'agent_one_integral' => $request->agent_one_integral,
            'agent_two_integral' => $request->agent_two_integral,
            'agent_three_integral' => $request->agent_three_integral,
            'sort' => $request->sort,
            'desc' => $request->desc,
            'detail'=> $request->detail,
            'category_id'=> $request->categoryid,
            'is_hot'=> $request->is_hot,
            'vocational'=> $request->vocational,
            'images'=> implode(',',array_filter($request->images)),
        ]);
//        print_r((Goods::find($request->id)->attributes)[0]->attributelists);exit;
        $aattributes = Goods::find($request->id)->attributes;
        Goods::abute($ab,$request->id,$aattributes);
        return back()->with(['message' => '更新成功']);
    }

    public function delete(Request $request)
    {
        //Goods::where('id', $request->id)->delete();
        //return response()->json(responseFormat(1, 'success'));
        Goods::where('id', $request->id)->update(['status'=>0]);
        return response()->json(responseFormat(1, 'success'));
    }

    public function grounding(Request $request)
    {
        //Goods::where('id', $request->id)->update(['status'=>2]);
        Goods::where('id', $request->id)->update(['status'=>1]);
        return response()->json(responseFormat(1, 'success'));
    }

    public function sort(Request $request){
        Goods::where('id', $request->id)->update(['sort'=>$request->sort]);
        return response()->json(responseFormat(1, 'success'));
    }

    /**
     * 图片上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(Request $request)
    {
        $file = $request->file('file');
        $allowedExtensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
            return response()->json(responseFormat(0, '不允许的文件类类型'));
        }
        $destinationPath = public_path('storage/goods');
        $extension = $file->getClientOriginalExtension();
        $fileName = str_random(10). '.' .$extension;
        $file->move($destinationPath, $fileName);

        return response()->json(responseFormat(1, '上传成功', '/storage/goods/' . $fileName));
    }

    /**
     * 获取商品
     * @param Request $request
     * @return mixed
     */
    public function goodsList(Request $request)
    {
        $pageSize = $request->limit;

        $goods = Goods::paginate($pageSize ?? 20);

        return response()->json([
            "code" => 0,
            "msg" => "",
            "count" => $goods->total(),
            "data" => $goods->items()
        ]);
    }
}
