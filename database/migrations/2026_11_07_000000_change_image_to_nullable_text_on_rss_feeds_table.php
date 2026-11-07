<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageToNullableTextOnRssFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 2023_05_14_221803 の up/down が逆だったため、適用済みの環境では
     * image が varchar(255) のまま残っている。ここで text に揃える。
     * 画像が無いフィードは image に null を登録するため nullable にする。
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rss_feeds', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rss_feeds', function (Blueprint $table) {
            $table->string('image')->change();
        });
    }
}
