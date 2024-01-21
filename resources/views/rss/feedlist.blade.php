@extends('layouts.app')

@section('content')

<div id="feedlist">

  {{-- 検索フォーム --}}
  <form action="{{ url('/feedlist')}}" method="GET" class="input-group mb-3">
    {{ csrf_field() }}
    <input type="text" name="k" class="form-control" placeholder="検索キーワードを入力" autocomplete="off" value="{{$keyword}}">
    <div class="input-group-append">
      <button type="submit" name="add" class="btn btn-outline-secondary">検索</button>
    </div>
  </form>


  @foreach( $feedList as $feed )
    <div class="rss_feed_block">
      <a target="_brank" href="{{$feed['url']}}">
        <div class="title">{{$feed['title']}}</div>
        <div class="info_title">{!!$feed['info_title']!!}</div>
        <img src="{{$feed['image']}}">
        <div class="pubData_area">
          <div class="pubData_time">公開日時: {{ $feed['pubDate'] }}</div>
        </div>
      </a>
    </div>
  @endforeach

  {{-- ページネーション --}}
  <div class="d-flex justify-content-center">
    {{ $feedList->appends([ 'k' => $keyword ])->links('pagination::bootstrap-4') }}
  </div>  

</div>

@endsection