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
            // 1. Role (Penting untuk membedakan Siswa vs Ortu)
            $table->string('role')->default('siswa')->after('email'); 

            // 2. Kode Unik Siswa (Nanti digenerate otomatis, misal: SISWA-XA12)
            $table->string('user_code')->unique()->nullable()->after('id'); 

            // 3. Kelas (Khusus Siswa)
            $table->string('kelas')->nullable()->after('role'); 

            // 4. Kode Anak (Khusus Ortu - menyimpan input teks kode siswa)
            // Diubah dari unsignedBigInteger ke string agar cocok dengan controller
            $table->string('child_id_code')->nullable()->after('user_code'); 
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'user_code', 'kelas', 'child_id_code']);
        });
    }
};