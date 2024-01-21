@extends('layouts.app')

@section('content')

<div id="detail">
  <div class="rss_feed">

    {{-- 更新日時の表示--}}
    <div class="update_time_area">
      @if ( array_key_exists('lastBuildDate', $rss['response_info'][0]) )
        <div class="update_time">更新日時: {{ date('Y-m-d H:i:s', strtotime($rss['response_info'][0]['lastBuildDate'])) }}</div>
      @elseif( array_key_exists('dc:date', $rss['response_info'][0]) )
        <div class="update_time">更新日時: {{ date('Y-m-d H:i:s', strtotime($rss['response_info'][0]['dc:date'])) }}</div>
      @endif
    </div>

    {{-- Infoの表示--}}
    <div class="info_area">
      <h2>{{ $rss['response_info'][0]['title'] }}</h2>
      @if ( array_key_exists('description', $rss['response_info'][0]) )
        <p>{{ $rss['response_info'][0]['description'] }}</p>
      @endif
      <a href="/api/get?url={{urlencode($rss['request_url'])}}">API</a>
    </div>

    {{-- Feedの表示 --}}
    @foreach( $rss['response_feed'] as $feed )
      <div class="feed_block">
        <div class="title">{{ $feed['title'] }}</div>
        @if ( array_key_exists('description', $feed) )
          <div class="description">{!! $feed['description'] !!}</div>
        @endif

        @if ( array_key_exists('dc:date', $feed) )
          <div>{{ $feed['dc:date'] }}</div>
        @endif

        @if ( array_key_exists('link', $feed) )
          <div class="url">
            <a href="{{ $feed['link'] }}" >{{ $feed['link'] }}</a>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</div>

@endsection
