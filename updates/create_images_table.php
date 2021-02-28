<?php

namespace Azeyn\Galleries\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateImagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('azeyn_galleries_images', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('path')->unique();
            $table->text('metadata')->default('{}');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('azeyn_galleries_images');
    }
}
