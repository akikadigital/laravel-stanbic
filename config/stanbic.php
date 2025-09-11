<?php

// config for Akika/LaravelStanbic
return [
    /*
    * The file system disk that we will be reading and writing
    */
    'disk' => env('STANBIC_FILESYSTEM_DISK', 'sftp'),
    'input_root' => env('STANBIC_INPUT_ROOT', ''),
    'output_root' => env('STANBIC_OUTPUT_ROOT', ''),
];
