<?php

namespace Azeyn\Galleries\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateModelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('azeyn_galleries_models', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::create('azeyn_galleries_model_image', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('model_id')->unsigned();
            $table->integer('image_id')->unsigned();
            $table->foreign('model_id')->references('id')->on('azeyn_galleries_models');
            $table->foreign('image_id')->references('id')->on('azeyn_galleries_images');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('azeyn_galleries_model_image');
        Scheme::dropIfExists('azeyn_galleries_models');
    }
}
