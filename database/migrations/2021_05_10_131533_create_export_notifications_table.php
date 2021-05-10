<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('campaign_id');
            $table->string('filename');
            $table->string('file_hash')->unique();
            $table->string('hash')->unique();
            $table->string('status', 50)->default('PENDING');
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
        Schema::dropIfExists('export_notifications');
    }
}
