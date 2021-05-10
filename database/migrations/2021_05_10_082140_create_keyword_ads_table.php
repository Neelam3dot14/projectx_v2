<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id');
            $table->foreignId('keyword_id');
            $table->foreignId('keyword_group_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('ad_type', 100);
            $table->string('ad_position', 100);
            $table->string('ad_title', 200)->nullable();
            $table->text('link');
            $table->text('ad_visible_link')->nullable();           
            $table->text('ad_link')->nullable();
            $table->text('ad_text')->nullable();
            $table->text('ad_status')->nullable();
            $table->integer('tries')->default(0);
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
        Schema::dropIfExists('keyword_ads');
    }
}
