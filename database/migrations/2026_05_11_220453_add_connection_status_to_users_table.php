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
        Schema::table('users', function (Blueprint $table) {
            // Akan berisi 'pending' atau 'approved'
            $table->string('child_connection_status')->nullable()->after('child_id_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('child_connection_status');
        });
    }
};
