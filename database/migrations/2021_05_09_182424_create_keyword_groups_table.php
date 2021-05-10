<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->string('keyword', 100);
            $table->string('device', 100);
            $table->string('search_engine', 100);
            $table->string('country_code', 200);
            $table->longText('states')->nullable();
            $table->longText('location');
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
        Schema::dropIfExists('keyword_groups');
    }
}
