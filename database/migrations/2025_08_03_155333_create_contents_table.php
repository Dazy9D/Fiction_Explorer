<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('release_date');
            $table->enum('type', ['movie', 'series']);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
