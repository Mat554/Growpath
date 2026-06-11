<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('exam_results', function (Blueprint $table) {
        // Menambahkan kolom status dengan nilai default 'published' agar langsung bisa dilihat
 $table->string('status')->default('published')->after('user_id'); 
    });
}

public function down()
{
    Schema::table('exam_results', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
