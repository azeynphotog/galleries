<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCollectionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('azeyn_galleries_collections', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamp('date')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();
        });
        Schema::create('azeyn_galleries_collection_image', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('collection_id')->unsigned();
            $table->integer('image_id')->unsigned();
            $table->foreign('collection_id')->references('id')->on('azeyn_galleries_collections')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('azeyn_galleries_images')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('azeyn_galleries_collection_image');
        Schema::dropIfExists('azeyn_galleries_collections');
    }
}
