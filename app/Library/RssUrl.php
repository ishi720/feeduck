<?php

namespace app\Library;

use phpQuery;

class RssUrl
{
    public static function get($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //Locationをたどる
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //最大何回リダイレクトをたどるか
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); //リダイレクトの際にヘッダのRefererを自動的に追加させる
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16 ( .NET CLR 3.5.30729)');//user agent設定

        $html = curl_exec($ch);

        // 見出し要素を取得&表示
        $phpQuery = phpQuery::newDocument($html);

        $rssArray = [];

        $countRSS = count($phpQuery['link[type=application/rss+xml]']);
        for ($i = 0; $i < $countRSS; $i++) {
            array_push($rssArray, $phpQuery["link[type=application/rss+xml]:eq(${i})"]->attr('href'));
        }

        $countAtom = count($phpQuery['link[type=application/atom+xml]']);
        for ($i = 0; $i < $countRSS; $i++) {
            array_push($rssArray, $phpQuery["link[type=application/atom+xml]:eq(${i})"]->attr('href'));
        }

        return $rssArray;
    }
}