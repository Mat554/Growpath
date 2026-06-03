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
        // Menambahkan kolom status dengan nilai default 'completed'
        $table->string('status')->default('completed')->after('user_id'); 
    });
}

public function down()
{
    Schema::table('exam_results', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
