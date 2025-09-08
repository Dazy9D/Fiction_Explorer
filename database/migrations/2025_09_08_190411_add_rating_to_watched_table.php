<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('watched', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->nullable()->after('content_id');
        });
    }

    public function down()
    {
        Schema::table('watched', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};
