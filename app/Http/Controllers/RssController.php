<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\RssData;
use App\Library\RssRegister;
use App\Library\RssUrl;
use App\Library\GooLab;

use App\RssInfo;
use App\RssFeed;

use Illuminate\Support\Facades\Auth;

class RssController extends Controller
{
	// RSS登録画面
    public function registration(Request $request){

        /*
         status
         - 成功(1x)
            - 10: DBに存在している(更新しました)
            - 11: 登録しました
         - 失敗(2x)
            - 20: URLが存在しない
            - 21: 登録できるデータフォーマットではない
            - 29: 異常系
         */

        $status = 0;

        if ( $request->url ) {
            $url = $request->url;
            $status = RssRegister::upsert($url);
        }
        return view('rss.registration',compact('status'));
    }

    public function edit(Request $request){
        $id = $request->input('info_id');

        if ($request->manual_tags) {
            $query = RssInfo::find($id);
            $query->manual_tags = $request->manual_tags;
            $query->save();
        }
        if ($request->access) {
            $query = RssInfo::find($id);
            $query->access = $request->access;
            $query->save();
        }
        if ($request->crawl_flag) {
            $query = RssInfo::find($id);
            $query->crawl_flag = $request->crawl_flag;
            $query->save();
        }

        $info = RssInfo::find($id);

        return view('rss.edit',compact('info'));
    }

	// 一覧検索画面
    public function search(Request $request){

        $keyword = '';

        if ($request) {
            //キーワードを配列化する
            $keyword = $request->k;
            $keywordArray = keywordSplit($keyword);
        }
        $query = RssInfo::query();

        //検索条件
        $query->where( function($query) use($keywordArray) {
            foreach ($keywordArray as $key => $value) {
                $query->orWhere('title', 'LIKE', '%'. $value .'%');
                $query->orWhere('description', 'LIKE', '%'. $value .'%');
                $query->orWhere('tag', 'LIKE', '%'. $value .'%');
                $query->orWhere('manual_tags', 'LIKE', '%'. $value .'%');
            }
        });

        // 未ログインユーザーには、許可したものしか表示しない
        if (!Auth::check()) {
            $query->Where('access', 'Like', 'public');
        }

        $rssList = $query->paginate(10);

        $page = $request->input('page') ? $request->input('page') : 1;

        return view('rss.search',compact('rssList','keyword','page'));
    }

    // feed一覧画面
    public function feedlist(Request $request){

        $keyword = '';
        if ($request) {
            //キーワードを配列化する
            $keyword = $request->k;
            $keywordArray = keywordSplit($keyword);
        }
        $query = RssFeed::select([
                    'info.title as info_title',
                    'feed.title as title',
                    'feed.image as image',
                    'feed.url as url',
                    'feed.pubDate as pubDate',
                ])
                ->from('rss_feeds as feed')
                ->join('rss_infos as info', function($join){
                    $join->on('feed.info_id','=','info.id');
                });

        $query->Where('info.access', 'Like', 'public');

        //検索条件
        $query->where( function($query) use($keywordArray) {
            foreach ($keywordArray as $key => $value) {
                $query->orWhere('feed.title', 'LIKE', '%'. $value .'%');
                $query->orWhere('feed.description', 'LIKE', '%'. $value .'%');
                $query->orWhere('info.tag', 'LIKE', '%'. $value .'%');
                $query->orWhere('info.manual_tags', 'LIKE', '%'. $value .'%');
                $query->orWhere('info.title', 'LIKE', '%'. $value .'%');
            }
        });

        $query->orderBy('feed.pubDate', 'desc');

        $feedList = $query->paginate(10);

        return view('rss.feedlist',compact('feedList','keyword'));
    }

	// 詳細画面
    public function detail(Request $request){

        $rss_url = $request->input('rss_url');

        $rss = RssData::get($rss_url);

        return view('rss.detail',compact('rss'));
    }
}

function keywordSplit($text) {
    $text = preg_replace('/　/',' ',$text);
    $text = preg_replace('/\s+/',' ',$text);
    $keywordArray = explode(' ', $text);
    return $keywordArray;
}


