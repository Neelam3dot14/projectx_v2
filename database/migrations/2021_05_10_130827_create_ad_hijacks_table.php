<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdHijacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_hijacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_trace_id');
            $table->foreignId('ad_id');
            $table->foreignId('campaign_id');
            $table->text('traced_domain');
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
        Schema::dropIfExists('ad_hijacks');
    }
}
