<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->string('basic_info', 255);
            $table->string('short_description', 255);
            $table->text('description');
            $table->string('capacity', 255);
            $table->string('transport_package', 255);
            $table->boolean('customization')->default(false);
            $table->string('payment_terms', 255);
            $table->string('delivery', 255);
            $table->string('min_order', 255);

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
        Schema::dropIfExists('products');
    }
}
