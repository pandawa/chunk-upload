<?php

use Pandawa\ChunkUpload\Filesystem\Factory\AliyunChunkStorageMacroFactory;

return [
    'upload_handler'    => env('CHUNK_UPLOAD_HANDLER', 'resumablejs'),
    'storage_factories' => [
        'oss' => AliyunChunkStorageMacroFactory::class,
    ],
];
