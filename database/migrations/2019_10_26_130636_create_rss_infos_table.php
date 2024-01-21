<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRssInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('text');
            $table->string('title');
            $table->text('link');
            $table->text('description');
            $table->string('rss_version');
            $table->string('tag');
            $table->string('access');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rss_infos');
    }
}
