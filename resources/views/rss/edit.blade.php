@extends('layouts.app')

@section('content')

<div id="edit">

	<h2>編集画面</h2>

	<form action="{{ url('/edit?info_id='. $info['id'])}}" method="POST">

		<div class="container">


			{{ csrf_field() }}

			<div class="title row">
				<label class="col">タイトル</label>
				<input class="col" type="text" value="{{$info['title']}}" disabled>
			</div>
			<div class="url row">
				<label class="col">URL</label>
				<input class="col" type="text" value="{{$info['link']}}" disabled>
			</div>
			<div class="description row">
				<label class="col">説明文</label>
				<input class="col" type="text" value="{{$info['description']}}" disabled>
			</div>

			<div class="manual_tags row">
				<label class="col">手動タグ</label>
				<input class="col" type="text" name="manual_tags" value="{{$info['manual_tags']}}">
			</div>
			<div class="tag row">
				<label class="col">自動タグ</label>
				<input class="col" type="text" value="{{$info['tag']}}" disabled>
			</div>
			<div class="access row">
				<label class="col">公開</label>
				<input class="col" type="text" name="access" value="{{$info['access']}}">
			</div>
			<div class="crawl_flag row">
				<label class="col">クロール</label>
				<input class="col" type="text" name="crawl_flag" value="{{$info['crawl_flag']}}">
			</div>
			<div class="created_at row">
				<label class="col">登録日時</label>
				<input class="col" type="text" value="{{$info['created_at']}}" disabled>
			</div>
			<div class="updated_at row">
				<label class="col">更新日時</label>
				<input class="col" type="text" value="{{$info['updated_at']}}" disabled>
			</div>

		</div>

		<div>
			<button type="submit" name="update_info" class="btn btn-outline-secondary">保存</button>
			<a class="btn btn-outline-secondary" href="./search">戻る</a>
			<button class="btn btn-outline-secondary">削除</button>
		</div>
	</form>
</div>

@endsection