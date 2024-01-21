@extends('layouts.app')

@section('content')
<div id="search">

  {{-- 検索フォーム --}}
  <form action="{{ url('/search')}}" method="GET" class="input-group mb-3">
    {{ csrf_field() }}
    <input type="text" name="k" class="form-control" placeholder="検索キーワードを入力" autocomplete="off" value="{{$keyword}}">
    <div class="input-group-append">
      <button type="submit" name="add" class="btn btn-outline-secondary">検索</button>
    </div>
  </form>

  {{-- 一覧表示 --}}
  @foreach( $rssList as $rss )
    <div class="rss_info_block {{ $rss['access'] == 'private' ? 'private' : '' }}">
      <a href="/detail?rss_url={{urlencode($rss['url'])}}">
        <div class="title">{{$rss['title']}}</div>
        <div class="rss_version">{{$rss['rss_version']}}</div>

        @foreach(  explode(',', $rss['manual_tags']) as $tag ) 
          <span class="manual_tags badge badge-success">{{ $tag }}</span>
        @endforeach

        <div class="description">{{$rss['description']}}</div>

        @if ( Auth::check() )
          <form action="{{ url('/edit?info_id='. $rss['id'])}}" method="POST" class="input-group mb-3">
            {{ csrf_field() }}
            <button type="submit" name="edit" value="{{$rss['id']}}" class="btn btn-outline-secondary">編集</button>
          </form>
        @endif

      </a>
    </div>
  @endforeach

  {{-- ページネーション --}}
  <div class="d-flex justify-content-center">
    {{ $rssList->appends([ 'k' => $keyword ])->links('pagination::bootstrap-4') }}
  </div>
</div>

@endsection
