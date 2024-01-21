<?php

namespace app\Library;

class GooLab
{
    public static function keywordExtraction($title,$body) {
        /*
         *  キーワード抽出API
         * https://labs.goo.ne.jp/api/jp/keyword-extraction/
        */

        $url = 'https://labs.goo.ne.jp/api/keyword';
        //$title = 'gooウェブ検索 急上昇ワード';
        //$body = '急上昇ワード情報を発信します。';

        $params = [
            'app_id' => config('const.goo_lab_api.app_id'),
            'title' => $title,
            'body' => $body,
            'max_num' => 5
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);


        json_decode($response);
        $gooKeywordArray = Array();
        foreach(json_decode($response)->keywords as $value){
            foreach($value as $key => $data){
                array_push($gooKeywordArray,$key);
            }
         }
        $gooKeyword = implode(",",$gooKeywordArray);

        return $gooKeyword;
    }
}