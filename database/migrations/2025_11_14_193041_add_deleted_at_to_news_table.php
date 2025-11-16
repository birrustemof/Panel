<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToNewsTable extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->softDeletes(); // Bu deleted_at sütunu əlavə edəcək
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
