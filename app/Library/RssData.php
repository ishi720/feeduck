<?php

namespace app\Library;

class RssData
{
    public static function get($url) {
        $data = array();
        $rss_url = htmlspecialchars($url,ENT_QUOTES, "UTF-8");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        $content = curl_exec($ch);

        $rssdata = simplexml_load_string($content);

        $format = RssData::rss_format_get($rssdata);

        //ATOM
        if($format == "ATOM"){
            $info_data = RssData::atom_info_get($rssdata);
            $feed_data = RssData::atom_feed_get($rssdata);
        }
        //RSS1.0
        elseif($format == "RSS1.0"){
            $info_data = RssData::rss1_info_get($rssdata);
            $feed_data = RssData::rss1_feed_get($rssdata);
        }
        //RSS2.0
        elseif($format == "RSS2.0"){
            $info_data = RssData::rss2_info_get($rssdata);
            $feed_data = RssData::rss2_feed_get($rssdata);
        }
        else {
            print("FORMAT ERROR\n");exit;
        }

        $response = array();
        $response['error_status'] = "0";
        $response['response_feed_count'] = count($feed_data);
        $response['request_url'] = $rss_url;
        $response['rss_format'] = $format;
        $response['response_info'] = $info_data;
        $response['response_feed'] = $feed_data;

        return $response;
    }



    private static function rss_format_get($rssdata){
        if($rssdata->entry){
            //ATOM
            return "ATOM";
        } elseif ($rssdata->item){
            //RSS1.0
            return "RSS1.0";
        } elseif ($rssdata->channel->item){
            //RSS2.0
            return "RSS2.0";
        } else {
            print("FORMAT ERROR");
            exit;
        }
    }
    // info_get
    private static function rss1_info_get($rssdata){
        foreach ($rssdata->channel as $channel) {
            $work = array();
            foreach ($channel as $key => $value) {
                $work[$key] = (string)$value;
            }
            //dc
            foreach ($channel->children('dc',true) as $key => $value) {
                $work['dc:'. $key] = (string)$value;
            }
            $data[] = $work;
        }
        return $data;
    }
    private static function rss2_info_get($rssdata){
        foreach ($rssdata->channel as $channel) {
            $work = array();
            foreach ($channel as $key => $value) {
                $work[$key] = (string)$value;
            }
            $data[] = $work;
        }
        return $data;
    }
    private static function atom_info_get($rssdata){
        foreach ($rssdata as $key => $item){
            if ($key === 'entry') {
                continue;
            }
            $work = array();
            $work[$key] = (string)$item;
            $data[] = $work;
        }
        return $data;
    }
    // feed_get
    private static function rss1_feed_get($rssdata){
        foreach ($rssdata->item as $item) {
            $work = array();
            foreach ($item as $key => $value) {
                $work[$key] = (string)$value;
            }
            //dc
            foreach ($item->children('dc',true) as $key => $value) {
                $work['dc:'. $key] = (string)$value;
            }
            //content
            foreach ($item->children('content',true) as $key => $value) {
                $work['content:'. $key] = (string)$value;
            }
            $data[] = $work;
        }
        return $data;
    }
    private static function rss2_feed_get($rssdata){
        foreach ($rssdata->channel->item as $item) {
            $work = array();
            foreach ($item as $key => $value) {
                $work[$key] = (string)$value;
            }
            //content
            foreach ($item->children('content',true) as $key => $value) {
                $work['content:'. $key] = (string)$value;
            }
            //wfw
            foreach ($item->children('wfw',true) as $key => $value) {
                $work['wfw:'. $key] = (string)$value;
            }
            //slash
            foreach ($item->children('slash',true) as $key => $value) {
                $work['slash:'. $key] = (string)$value;
            }
            $data[] = $work;
        }
        return $data;
    }
    private static function atom_feed_get($rssdata){
        foreach ($rssdata->entry as $item){
            $work = array();
            foreach ($item as $key => $value) {
                if( $key == "link"){
                    $work[$key] = (string)$value->attributes()->href;;
                } else {
                    $work[$key] = (string)$value;
                }
            }
            //dc
            foreach ($item->children('dc',true)  as $key => $value) {
                $work['dc:'. $key] = (string)$value;
            }
            $data[] = $work;
        }
        return $data;
    }
}