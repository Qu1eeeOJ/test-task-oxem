<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Product;

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
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->integer('quantity');
            $table->integer('category_id');
            $table->integer('external_id');
            $table->timestamps();
        });

        for ($i = 0; $i <= 150; $i++) {
            $model = new Product;
            $model->name = "Тест - $i";
            $model->description = "Описание - $i";
            $model->price = $i;
            $model->quantity = $i + 10;
            $model->category_id = 1;
            $model->external_id = 1;
            $model->save();
        }
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
