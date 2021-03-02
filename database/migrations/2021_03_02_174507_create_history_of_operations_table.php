<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryOfOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_of_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('operation_type');
            $table->unsignedTinyInteger('model_type');
            $table->unsignedInteger('model_id');
            $table->jsonb('model');
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
        Schema::dropIfExists('history_of_operations');
    }
}
