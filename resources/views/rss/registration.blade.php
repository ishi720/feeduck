@extends('layouts.app')

@section('content')

<div id="extraction">

  <p>RSSのURLを入力してください</p>

  {{-- 登録フォーム --}}
  <form action="{{ url('/registration')}}" method="POST" class="input-group mb-3">
    {{ csrf_field() }}
    <input type="url" class="form-control" name="url" autocomplete="off">
    <div class="input-group-append">
      <button type="submit" name="add" class="btn btn-outline-secondary">追加</button>
    </div>
  </form>


  {{-- 結果表示 --}}
  @if ($status == 20)
    <div>Urlが存在しませんでした</div>
  @elseif ($status == 11)

    <div>登録完了</div>

  @elseif ($status == 10)
    <div>更新完了</div>
  @endif

</div>


@endsection