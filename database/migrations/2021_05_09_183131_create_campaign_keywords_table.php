<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->foreignId('keyword_group_id');
            $table->string('keyword', 100);
            $table->string('proxy_use', 50)->default('default');
            $table->string('device', 50)->default('desktop');
            $table->string('search_engine', 50)->default('google');
            $table->string('country_code', 50)->nullable();
            $table->string('lang', 20)->nullable();
            $table->string('google_domain', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_keywords');
    }
}
