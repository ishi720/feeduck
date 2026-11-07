<?php

namespace App\Library;

use Symfony\Component\DomCrawler\Crawler;

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

        // 取得できなかった場合は空
        if (!is_string($html) || $html === '') {
            return [];
        }

        $crawler = new Crawler($html);

        // RSS → Atom の順にlinkタグのhrefを取得する
        $rssArray = [];
        foreach (['application/rss+xml', 'application/atom+xml'] as $type) {
            $hrefs = $crawler->filter('link[type="' . $type . '"]')->each(
                fn (Crawler $node) => $node->attr('href')
            );
            array_push($rssArray, ...$hrefs);
        }

        return $rssArray;
    }
}