<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public $fillable = [
        'title' ,
        'telephone_fare' ,
        'price' ,
        'original_price' ,
        'carriage' ,
        'stock_quantity' ,
        'sale_quantity' ,
        'seller_one_integral',
        'seller_two_integral',
        'seller_three_integral' ,
        'agent_one_integral' ,
        'agent_two_integral' ,
        'agent_three_integral' ,
        'sort' ,
        'status' ,
        'desc' ,
        'detail',
        'images',
        'category_id',
        'is_hot',
        'vocational',
    ];
    public function getStatusAttribute($value){
        if($value == 1){
            return '<span style="color:green">已上架</span>';
        }else{
            return '<span style="color:red">已下架</span>';
        }
    }

    public function getImagesAttribute($value)
    {
        return array_filter(explode(',',$value));
//        return json_decode($value,1);
    }
//
//    public function setImagesAttribute($value)
//    {
//        return $this->attributes['images'] = json_encode($value,1);
//    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function attributes()
    {
        return $this->hasMany('App\Models\Attribute','goods_id');
    }
    //对商品属性入库处理
    public static function abute($attributedata,$goodsid,$aattributes='')
    {
        //清空属性
        if(count($aattributes)){
            foreach ($aattributes as $attribute) {
                if(count($attribute->attributelists)){
                    foreach ($attribute->attributelists as $attributelist) {
                        $attributelist->delete();
                    }
                }
                $attribute->delete();
            }
        }
        $attributedata = explode('|',trim($attributedata,'|'));
        if(!count($attributedata)){
            return true;
        }
        foreach ($attributedata as $attribute) {
            $goodsAttribute = array_filter(explode(',',$attribute));
            if(!count($goodsAttribute)){
                continue;
            }
            $attributeName = $goodsAttribute[0];
            $attributeList = array_slice($goodsAttribute,1);
            $attribute = new Attribute();
            $attribute->goods_id = $goodsid;
            $attribute->name = $attributeName;
            if(!$attribute->save()){
                return false;
            }
            foreach ($attributeList as $item) {
                $attributelist = new Attributelist();
                $attributelist->attribute_id = $attribute->id;
                $attributelist->name = $item;
                if(!$attributelist->save()){
                    return false;
                }
            }
        }
        return true;
    }

    
}
