<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CollectionImageAddOrder extends Migration
{
    public function up(): void
    {
        Schema::table('azeyn_galleries_collection_image', function ($table) {
            $table->integer('order')->unsigned()->unique()->after('image_id');
        });
    }

    public function down(): void
    {
        Schema::table('azeyn_galleries_collection_image', function ($table) {
            $table->dropColumn('order');
        });
    }
}
