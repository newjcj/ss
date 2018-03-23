<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getImageAttribute($value)
    {
        return array_filter(explode(',',$value));
    }

    public function goods()
    {
        return $this->hasMany('App\Models\Goods','category_id');
    }
    //获取分类下所有商品（含子分类）
    public static function getCategoryGoods($cid,$znum='6'){
        $s=Category::where('pid',$cid)->get();
        $arr=[];
        foreach ($s as $g){
            if(!count($arr)){$arr=$g->goods;}else{
                $arr.=$g->goods;
            }

        }

        //print_r($arr);exit;
        if(count($arr)<$znum){$znum=count($arr);}
        if($znum==0){return false;}
        //print_r($arr);exit;
        //return array_rand($arr,$znum);
        return $arr;
    }

    //getTop
    public static function getTopCategory()
    {
        //缓存起来
        if(Cache::has('cone')){
            return Cache::get('cone');
        }else{
            $cone = [];
            foreach (Category::where('pid',1)->get() as $k=>$v) {
                $cone[$k]['id']=$v->id;
                $cone[$k]['name']=$v->name;
                $cone[$k]['category']=Category::where('pid',$v->id)->get();
            }
            Cache::put('cone',$cone,20);
            return $cone;
        }
    }

    //通过pid取自身path
    public function getPath($pid)
    {
        $pcategory = Category::find($pid);
        return $pcategory->path.'-'.$pcategory->id;
    }
    //通用分类列表option
    public function getOptions($id=1){
        $scategory = Category::find($id);
        $categorys=DB::table('category')
            ->select(DB::raw('image,id, name,path, concat(path,"-",id) as orderpath'))
            ->orderBy('orderpath')
            ->where('id','!=',0)->get();
        $str='';
        $color=["#FF69B4",'#800080','#aaa','#0000FF','#1E90FF','#2F4F4F'];
        foreach ($categorys as $category){
            $select='';
            if($category->id == $id)$select="selected";
            $num = count(explode('-',$category->path.'-'.$category->id))-2;
            $st = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$num);
            $str.="<option value={$category->id} {$select} style='color:{$color[$num]}'>  {$st}{$category->name}</option>";
        }
        return $str;
    }
    //判断要分到的父类是不是自己的子类
    public static function haveCcategory($pid,$id)
    {
        $category = Category::find($id)->path;
        $pcategory = Category::find($pid)->path;
        $count = count(explode('-',$category));
        $pcount = count(explode('-',$pcategory));
        if($category == $pcategory){
            return false;
        }
        if($pcount < $count){
            return true;
        }
        $p1 = $category.'-'.$id;
        $p2=implode('-',array_slice(explode('-',$pcategory),0,$count+1));
        if($p1 == $p2){
            return false;
        }else{
            return true;
        }
    }
    //判断自己类目下有没有分类
    public static function haveCategory($id)
    {
        $category = Category::find($id);
        $re= DB::select('select * from category where path like ?', [$category->path.'-'.$id.'%']);
        return count($re)>0?true:false;
    }
    //判断分类下有没有商品
    public static function haveGoods($id){
        if(count(Goods::where('category_id',$id)->get())){
            return true;
        }else{
            return false;
        }
    }
}
