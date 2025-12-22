<?php

// config for Akika/LaravelStanbic
return [
    /*
    * The file system disk that we will be reading and writing
    */
    'disk' => env('STANBIC_FILESYSTEM_DISK', 'sftp'),
    'input_root' => env('STANBIC_INPUT_ROOT', 'Inbox'),
    'output_root' => env('STANBIC_OUTPUT_ROOT', 'Outbox'),

    /*
    |--------------------------------------------------------------------------
    | Output File Prefix
    |--------------------------------------------------------------------------
    | As of 2025/10/07, the below is a sample of the recommended file name formats:
    |  - For the test/uat environment: MY_COMPANYC2C_Pain001v3_GH_TST_yyyymmddhhmmssSSS.xml
    |  - For the production environment: MY_COMPANYC2C_Pain001v3_GH_PRD_yyyymmddhhmmssSSS.xml
    |
    | As such, the prefix can be, for example, either:
    |  - MY_COMPANYC2C_Pain001v3_GH_TST_  or
    |  - MY_COMPANYC2C_Pain001v3_GH_PRD_
    */
    'output_file_prefix' => env('STANBIC_OUTPUT_FILE_PREFIX'),
];
