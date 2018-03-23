<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class CategoryController extends Controller
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
    public function list(Request $request)
    {
        $categorys=DB::table('category')
            ->select(DB::raw('image,id, name,path, concat(path,"-",id) as orderpath'))
            ->orderBy('orderpath')
            ->where('id','!=',0)->get();
        $re=[];
        foreach ($categorys as $category) {
            $category->image = explode(',',$category->image);
        }
        return view('backend.category.index',['categorys'=>$categorys]);
    }
    //编辑分类
    public function add(Request $request)
    {
        $category = new Category();
        return view('backend.category.add',['category'=>$category]);
    }
    //添加分类
    public function postadd(Request $request)
    {
        if(!$request->input('pid','') || !$request->input('name','')){

            r(0,'参数不全','/admin/category/list');
        }
        $category = new Category();
        $category->pid=$request->input('pid');
        $category->name=$request->input('name');
        $category->path=$category->getPath($request->input('pid'));
        $category->image=implode(',',array_filter($request->input('images',[])));
        if($category->save()){
            r(1,'添加成功','');
        }else{
            r(2,'保存失败','');
        }
    }
    //编辑分类
    public function detail(Request $request)
    {
        $category = Category::find($request->input('id'));
        return view('backend.category.detail',['category'=>$category]);
    }
    //编辑分类
    public function postdetail(Request $request)
    {
        $pid = $request->input('pid');
        $id = $request->input('id');
        $pcategory = Category::find($pid);
        $category = Category::find($id);
        $ppath = $pcategory->path;
        $path = $category->path.'-'.$id;//本身子类配置的path
        $spath = $pcategory->path.'-'.$pid;//本身要更改成的path
        $pspath = $pcategory->path.'-'.$pid.'-'.$id;//本身要更改成的path
        $sspath = $spath.'-'.$id;//本身子分类要替换的前缀path
        //更新所有自己类目下的分类path
        //判断要分到的父类是不是自己的子类
        if($return = $category->haveCcategory($pid,$id)){
            DB::update('update category set path = replace(path,?,?) where path like ?', [$path,$pspath,$path.'%']);
        }else{
            $return = false;
        }

        //更新自己的path
        $category->path=$spath;
        $category->pid=$pid;
        $category->name=$request->input('name');
        $category->image=implode(',',array_filter($request->input('images',[])));
//        $category->image2=$request->input('image2');
        if ($return && $category->save()){
            r(1,'更新成功','/admin/goods/category/index');
        }else{
            r(0,'更新失败','admin/goods/category/index');
        }
    }
    //删除分类
    public function postdelete(Request $request)
    {
        //分类下还有分类则不能删除
        if(!Category::haveCategory($request->input('id')) && !Category::haveGoods($request->input('id'))){
            if(Category::find($request->input('id'))->delete()){
                r(1,'删除成功','/admin/category/list');
            }else{
                r(0,'删除失败','admin/category/list');
            }
        }else{
            r(2,'分类下有商品或还有了分类','admin/category/list');
        }

    }
    //上传图片
    public function img(Request $request)
    {
        print_r($request->except('id'));exit;
        print_r($_GET);exit;

    }
}
