<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\RssData;

use App\RssInfo;

class ApiController extends Controller
{
    // RSSをjson表示する
    public function RssGet(Request $request){

        $url = $request->url;
        $data = RssData::get($url);

        $json = json_encode($data);

        return response($json, 200)
            ->header('Cache-Control', 'no-cache')
            ->header('Content-Type', 'application/json')
            ->header('Content-Length', strlen($json));
    }

    // 外部からRSSを登録する
    public function RssSet(Request $request){

        $url = $request->url;

        // DBに登録済みかチェックする
        $dataExistFlag = RssInfo::select(['id', 'url'])
                            ->orwhere('url',$url)
                            ->count();

        if ( $dataExistFlag !== 0 ) {
            //データが存在するので終了
            return;
        }


        // RSS情報をjson化する
        $data = RssData::get($url);

        // RSSの詳細のみ取得してDBへ登録
        $rssinfo = $data['response_info'][0];
        $flight = RssInfo::create([
            'url' => $data['request_url'],
            'title' => array_key_exists('title', $rssinfo) ? $rssinfo['title'] : '',
            'link' => array_key_exists('link', $rssinfo) ? $rssinfo['link'] : '',
            'description' => array_key_exists('description', $rssinfo) ? $rssinfo['description'] : '',
            'rss_version' => array_key_exists('rss_format', $rssinfo) ? $rssinfo['rss_format'] : '',
            'tag' => '',
            'access' => 'public'
        ]);

        $json = json_encode( [
            'status' => 200,
            'url' => $url
        ]);
        return response($json, 200)
            ->header('Cache-Control', 'no-cache')
            ->header('Content-Type', 'application/json')
            ->header('Content-Length', strlen($json));

    }
}
