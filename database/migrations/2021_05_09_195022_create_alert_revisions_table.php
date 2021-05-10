<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_group_id');
            $table->foreignId('keyword_id');
            $table->longText('keyword');
            $table->longText('canonical_name');
            $table->longText('canonical_states')->nullable();
            $table->string('google_uule', 100);
            $table->longText('user_agent')->nullable();
            $table->longText('crawled_html')->nullable();
            $table->longText('crawling_error')->nullable();
            $table->longText('scraped_json')->nullable();
            $table->longText('scraping_error')->nullable();
            $table->longText('tracing_error')->nullable();
            $table->longText('crawler_metadata')->nullable();
            $table->longText('scraper_metadata')->nullable();
            $table->longText('tracer_metadata')->nullable();
            $table->string('status')->default('CRAWLING');
            $table->integer('flag')->default(0);
            $table->integer('global_tries')->default(0);
            $table->integer('crawler_tries')->default(0);
            $table->integer('scraper_tries')->default(0);
            $table->integer('tracer_tries')->default(0);
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
        Schema::dropIfExists('alert_revisions');
    }
}
