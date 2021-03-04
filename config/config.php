<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

return [
    'cache'=> [
        'path' => storage_path('temp/images'),
    ],
    // time() offset
    // Between 1/1/1970 and 1/1/2021 = 1609459200 seconds
    'offset' => 1609459200
];
