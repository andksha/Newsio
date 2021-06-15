<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesClosuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_closures', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('immediate_parent_id');
            $table->foreign('immediate_parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->primary(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories_closures');
    }
}
