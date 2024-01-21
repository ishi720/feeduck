<?php

namespace app\Library;

use App\RssInfo;
use App\RssFeed;

use phpQuery;

class RssRegister
{
    public static function upsertAll() {
        $urls = RssInfo::select()->pluck("url");
        foreach($urls as $key => $url){
            echo($url ."\n");
            RssRegister::upsert($url);
        }
    }
    public static function upsert($url) {
        // urlの存在チェック
        // TODO: RssDataの結果に含ませる
        if ( getHTTPstatus($url)[0] != 200 ){
            $status = 20;
            return view('rss.registration',compact('status'));
        }

        $data = RssData::get($url);
        
        $rssinfo = $data['response_info'][0];

        // DBに登録済みかチェックする
        // 0: 存在しない
        // 1: 存在する
        $dataExistFlag = RssInfo::select(['id', 'url'])
                            ->orwhere('url',$url)
                            ->count();
    
        //Infoの登録&更新
        RssRegister::infoUpsert($url);
        $info_id = RssInfo::where('url',$url)->pluck('id')[0];
        //Feedの登録&更新
        RssRegister::feedUpsert($info_id,$data);

        if ( $dataExistFlag === 0 ) {
            //新規登録
            $status = 11;
         } else {
            //更新
            $status = 10;
        }
        return view('rss.registration',compact('status'));
    }

    public static function infoUpsert($url) {
     
        $data = RssData::get($url);
        $rssinfo = $data['response_info'][0];

        if (RssInfo::where('url',$url)) {
            // $gooKeyword = GooLab::keywordExtraction(
            //     array_key_exists('title', $rssinfo) ? $rssinfo['title'] : '',
            //     array_key_exists('description', $rssinfo) ? $rssinfo['description'] : ''
            // );
            $gooKeyword = "";
        } else {
            $gooKeyword = "";
        }

        if (RssInfo::where('url',$url)->count() >= 1) {
            $info_id = RssInfo::where('url',$url)->pluck('id')[0];
        } else {
            $info_id = null;
        }

        RssInfo::upsert([[
            'id' => $info_id, 
            'url' => $data['request_url'],
            'title' => array_key_exists('title', $rssinfo) ? $rssinfo['title'] : '',
            'link' => array_key_exists('link', $rssinfo) ? $rssinfo['link'] : '',
            'description' => array_key_exists('description', $rssinfo) ? $rssinfo['description'] : '',
            'rss_version' => $data['rss_format'],
            //'tag' => $gooKeyword,
            'tag' => '',
            'manual_tags' => '',
            'crawl_flag' => 1,
            'access' => 'public'
        ]], 
        ['url'], 
        ['title', 'description','tag']);
    }

    public static function feedUpsert($info_id,$data) {

        if (RssFeed::where('info_id',$info_id)->count() >= 1) {
            $pubDate = RssFeed::where('info_id',$info_id)
                        ->orderBy('pubDate', 'desc')
                        ->pluck('pubDate')[0];
        } else {
            $pubDate = "1970-01-01 09:00:00";
        }

        foreach($data['response_feed'] as $key => $value){
            //画像の取得
            if ( array_key_exists('content:encoded', $value) ) {
                $htmldoc = phpQuery::newDocument($value['content:encoded']);
            } else if ( array_key_exists('description', $value) ) {
                $htmldoc = phpQuery::newDocument($value['description']);
            } else if ( array_key_exists('content', $value) ) {
                $htmldoc = phpQuery::newDocument($value['content']);
            } else {
                $htmldoc = "";
            }

            $imglink=$htmldoc->find("img")->attr("src");

            if ( array_key_exists('dc:date', $value) ) {
               $feedDate = date('Y-m-d H:i:s', strtotime($value['dc:date']));
            } else if ( array_key_exists('pubDate', $value) ) {
                $feedDate = date('Y-m-d H:i:s', strtotime($value['pubDate']));
            } else if ( array_key_exists('issued', $value)) {
                $feedDate = date('Y-m-d H:i:s', strtotime($value['issued']));
            } else {
                $feedDate = "1970-01-01 09:00:00";
            }

            if ($pubDate < $feedDate) {
                RssFeed::upsert([[
                    'title' => $value['title'],
                    'info_id' => $info_id, 
                    'url' => array_key_exists('link', $value) ? $value['link'] : '',
                    'description' => '',
                    'image' => $imglink,
                    'tag' => '',
                    'pubDate' => $feedDate
                ]], 
                ['url'], 
                ['title','image','tag','pubDate']);
            }
        }
    }

}

function getHTTPstatus($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    $n = preg_match("/^(HTTP\/[0-9\.]+) ([0-9]+) (.+)/i", $data, $arr);

    if (! isset($arr[2]))    $arr[2] = FALSE;
    if (! isset($arr[3]))    $arr[3] = '';
    $res = array($arr[2], $arr[3]);

    return $res;
}

function isDate($date) {
   if(date('Y-m-d H:i:s', strtotime($date)) === $date){
        return true;
    }else{
        return false;
    }
}