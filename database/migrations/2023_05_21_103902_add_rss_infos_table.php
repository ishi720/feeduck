<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRssInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rss_infos', function (Blueprint $table) {
            $table->string('manual_tags')->after('tag');
            $table->boolean('crawl_flag')->default(1)->after('manual_tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('manual_tags');
            $table->dropColumn('crawl_flag');
        });
    }
}
