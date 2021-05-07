<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('keywords');
            $table->string('device', 50)->default('Desktop');
            $table->string('search_engine', 50)->default('Google');
            $table->string('crawler', 250)->default('random');
            $table->string('execution_type', 50)->default('crawl');            
            $table->string('execution_interval', 100);
            $table->string('country', 255);
            $table->longText('canonical_states');
            $table->string('gl_code', 500)->nullable();
            $table->longText('google_domain')->nullable();
            $table->longText('location');
            $table->longText('blacklisted_domain')->nullable();
            $table->longText('whitelisted_domain')->nullable();
            $table->string('status', 50)->default('INACTIVE');
            $table->string('created_by', 100)->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
