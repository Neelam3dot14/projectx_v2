<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeotargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geotargets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('criteria_ID')->unique();
            $table->string('name', 100);
            $table->string('canonical_name', 500);
            $table->unsignedBigInteger('parent_ID')->nullable();
            $table->string('country_code', 2);
            $table->string('target_type', 100);
            $table->string('status', 50);
            $table->string('canonical_country', 255);
            $table->string('canonical_states', 255);
            $table->string('maxmind_country', 255);
            $table->string('maxmind_states', 255);
            $table->longText('uule_code');
            $table->string('google_domain', 255)->nullable();
            $table->longText('yahoo_domain')->nullable();
            $table->string('language', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geotargets');
    }
}
