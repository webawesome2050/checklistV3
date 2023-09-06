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
        Schema::create('check_lists_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checklist_id');
            $table->date('date_of_inspection');
            $table->time('start_time');
            $table->time('finish_time');
            $table->string('inspected_by');
            $table->string('approved_by');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('checklist_id')->references('id')->on('check_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_lists_detail');
    }
};
