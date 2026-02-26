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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->string('opt_r'); // Opsi Realistic
            $table->string('opt_i'); // Opsi Investigative
            $table->string('opt_a'); // Opsi Artistic
            $table->string('opt_s'); // Opsi Social
            $table->string('opt_e'); // Opsi Enterprising
            $table->string('opt_c'); // Opsi Conventional
            $table->boolean('is_active')->default(true); // Status Tayang/Draft
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
        Schema::dropIfExists('questions');
    }
};
