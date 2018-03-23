<?php

namespace App\Entity;
/*
 * http://www.cbooo.cn/BoxOffice/GetHourBoxOffice?d=1502166067096
 * http://wwwcbooocnBoxOfficeGetHourBoxOffice?d=1502166067096
 *
 * http://www.cbooo.cn/BoxOffice/GetTopRate?d=1502166859919
 * http://wwwcbooocnBoxOfficeGetTopRate?d=1502166859919
 *
 *http://www.cbooo.cn/BoxOffice/GetFutureBox?timeStr=0&d=1502167041230
 *http://wwwcbooocnBoxOfficeGetFutureBox?timeStr=0&d=1502167041230
 *
 *http://www.cbooo.cn/BoxOffice/GetDayBoxOffice?num=-1&d=1502167188350
 *http://wwwcbooocnBoxOfficeGetDayBoxOffice?num=-1&d=1502167188350
 *
 *http://www.cbooo.cn/BoxOffice/getDayInfoData
 *http://wwwcbooocnBoxOfficegetDayInfoData
 *
 *http://www.cbooo.cn/BoxOffice/getWeekInfoData?sdate=2017-07-31
 *http://wwwcbooocnBoxOfficegetWeekInfoData?sdate=2017-07-31
 *
 *http://www.cbooo.cn/BoxOffice/getWeekInfo_fData
 *http://wwwcbooocnBoxOfficegetWeekInfofData
 *
 *http://www.cbooo.cn/BoxOffice/getWeekendInfoData?selDate=2017-08-04%262017-08-06%7C2017-07-28%262017-07-30
 *http://wwwcbooocnBoxOfficegetWeekendInfoData?selDate=2017-08-04%262017-08-06%7C2017-07-28%262017-07-30
 *
 *http://www.cbooo.cn/BoxOffice/getWeekendInfo_fData?selDate=2017-07-31%262017-08-06%7C2017-07-24%262017-07-30
 *http://wwwcbooocnBoxOfficegetWeekendInfofData?selDate=2017-07-31%262017-08-06%7C2017-07-24%262017-07-30
 *
 *http://www.cbooo.cn/BoxOffice/getMonthBox?sdate=2017-08-01
 *http://wwwcbooocnBoxOfficegetMonthBox?sdate=2017-08-01
 *
 *http://www.cbooo.cn/BoxOffice/getMonthEndBox?nd=1502167399684
 *http://wwwcbooocnBoxOfficegetMonthEndBox?nd=1502167399684
 *
 *http://www.cbooo.cn/boxOffice/GetYearInfo_f?year=2017
 *http://wwwcbooocnboxOfficeGetYearInfof?year=2017
 *
 *http://www.cbooo.cn/BoxOffice/getAllInfo?weekId=4781
 *http://wwwcbooocnBoxOfficegetAllInfo?weekId=4781
 *
 *http://www.cbooo.cn/BoxOffice/getALLInfo_b?weekId=4782
 *http://wwwcbooocnBoxOfficegetALLInfob?weekId=4782
 *
 *http://www.cbooo.cn/BoxOffice/getALLInfo_x?weekId=4780
 *http://wwwcbooocnBoxOfficegetALLInfox?weekId=4780
 *
 *http://www.cbooo.cn/BoxOffice/getInland?pIndex=1&t=0
 *http://wwwcbooocnBoxOfficegetInland?pIndex=1&t=0
 *
 *http://www.cbooo.cn/BoxOffice/getCBD?pIndex=1&dt=2017-08-07
 *http://wwwcbooocnBoxOfficegetCBD?pIndex=1&dt=2017-08-07
 *
 *http://www.cbooo.cn/BoxOffice/getCBW?pIndex=1&dt=961
 *http://wwwcbooocnBoxOfficegetCBW?pIndex=1&dt=961
 *
 *
 * 排片
 *http://www.cbooo.cn/Screen/getScreenData?days=0
     *http://wwwcbooocnScreengetScreenData?days=0
 *
 *http://www.cbooo.cn/Screen/getAreaScreenData?days=0
 *http://wwwcbooocnScreengetAreaScreenData?days=0
 *
 *http://www.cbooo.cn/Screen/getCityData?days=0&cityType=1
 *http://wwwcbooocnScreengetCityData?days=0&cityType=1
 *
 *
 *http://www.cbooo.cn/Screen/getTrendScreenData
 *http://wwwcbooocnScreengetTrendScreenData
 *
 *
 * 中美
 * http://www.cbooo.cn/boxOffice/getUCDayBox?days=-7&y=0&d=1502242386843
 * http://wwwcbooocnboxOfficegetUCDayBox?days=-7&y=0&d=1502242386843
 *
 *http://www.cbooo.cn/boxOffice/getUS_tolBox?days=-7&y=0&d=1502242386855
 *http://wwwcbooocnboxOfficegetUStolBox?days=-7&y=0&d=1502242386855
 *
 *http://www.cbooo.cn/boxOffice/getMovies?type=1&years=2017
 *http://wwwcbooocnboxOfficegetMovies?type=1&years=2017
 *
 *http://www.cbooo.cn/boxOffice/getUC_compar?mid=656946
 *http://wwwcbooocnboxOfficegetUCcompar?mid=656946
 *
 *http://www.cbooo.cn/boxOffice/getUCTolBox?mid=656946
 *http://wwwcbooocnboxOfficegetUCTolBox?mid=656946
 *
 * http://www.cbooo.cn/boxOffice/getAsyncMovie?mid=658813&d=1502242725760
 * http://wwwcbooocnboxOfficegetAsyncMovie?mid=658813&d=1502242725760
 *
 *http://www.cbooo.cn/boxOffice/getAsyncTolBox?mid=658813
 *http://wwwcbooocnboxOfficegetAsyncTolBox?mid=658813
 *
 *
 * 资讯
 * http://www.cbooo.cn/Information/GetNewsList?pIndex=1
 * http://wwwcbooocnInformationGetNewsList?pIndex=1
 *
 *
 * 电视
 * http://www.cbooo.cn/Mess/GetDayPlays?tvType=2
 * http://wwwcbooocnMessGetDayPlays?tvType=2
 *
 *
 * 影库
 * http://www.cbooo.cn/Mdata/getMdata_movie?area=50&type=0&year=0&initial=%E5%85%A8%E9%83%A8&pIndex=1
 * http://wwwcbooocnMdatagetMdatamovie?area=50&type=0&year=0&initial=%E5%85%A8%E9%83%A8&pIndex=1
 *
 * http://www.cbooo.cn/Mdata/getMdate_pList?area=50&type=0&year=0&initial=%E5%85%A8%E9%83%A8&pIndex=1
 * http://wwwcbooocnMdatagetMdatepList?area=50&type=0&year=0&initial=%E5%85%A8%E9%83%A8&pIndex=1
 *
 *
 *
 */
//统一取数据  存数据类
class Mdata {
    const TIME_ONE = 60*3;//一个小时
    const TIME_TWO = 720;//12个小时
    const TIME_DAY = 1440;//一天
    const TIME_WEEK = 10080;//一周

    private $table = '';
    private $api='';
    private $query=[];

    //初始化数据库
    public function __construct($table)
    {
        $this->table=$table;
    }

    //取网页json 数据入库 更新数据入库   直接取数据  url(数据接口)  time(更新入库间隔时间，单位分钟)
    public  function setData($url='',$time){
        if($url != ''){
            $url = $url();
        }
        if($this->getUrl($url)->query == false){
            $data = $this->table->find(1);
        }else{
            foreach ($this->getUrl($url)->query as $k=>$v) {
                $table_where = $this->table->where($k,$v);
            }
            $data = $table_where->first();
        }

        if(!$data){
            $id = $this->saveDb($url);
            return $this->table->find($id);
        }
        $updateTime = strtotime($data->updated_at);
        if((time() - $updateTime) > ($time * 60) ){
            //超过更新时间入库
            $id = $this->saveDb($url);
            return $this->table->find($id);
        }
        return $data;

    }
    //入库
    public function saveDb($url){
        $table=$this->table;
        $table->data = $this->getJson($url);
        if($this->getUrl($url)->query == false){
            $table->save();
            return $table->id;
        }
        foreach($this->getUrl($url)->query as $k=>$v){
            $table->$k = $v;
        }
        $table->save();
        return $table->id;
    }
    //取数据
    public function getDb($url){
        if($this->getUrl($url)->query == false){
            $table_where = $this->table;
            $data = $table_where->find(1);
            if(!$data){
                return $this->saveDb($url);//入库
            }
            return $data;
        }
        foreach ($this->getUrl($url)->query as $k=>$v) {
            $table_where = $this->table->where($k,$v);
        }
        $data = $table_where->first();
        if(!$data){
            return $this->saveDb($url);//入库
        }
        return $data;
    }
    //取api
    public function getUrl($url){
        $arrurl = parse_url($url);
        if(!isset($arrurl['query'])){
            $arrurl['query']=[];
            $re=[];
            $re['query'] = false;
            $re['api'] = str_replace('.','',$arrurl['host']).str_replace('_','',str_replace('.','',str_replace('/','',$arrurl['path'])));
            $this->api=$re['api'];
            $this->query=$re['query'];
            return $this;
        }
        $re=[];
        $re['query']=[];
        $re['api'] = str_replace('.','',$arrurl['host']).str_replace('_','',str_replace('.','',str_replace('/','',$arrurl['path'])));
        $query = explode('&',$arrurl['query']);
        foreach($query as $v){
            $arr = explode('=',$v);
            $re['query'][$arr[0]]=$arr[1];
        }
        $this->api=$re['api'];
        $this->query=$re['query'];
        return $this;
    }
    //通过api取json数据
    public function getJson($url){
        return Curl::vget($url);
    }




}
