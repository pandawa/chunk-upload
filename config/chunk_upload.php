<?php

return [
    'upload_handler'    => env('CHUNK_UPLOAD_HANDLER', 'resumablejs'),
    'storage_factories' => [
        'oss' => Pandawa\ChunkUpload\Filesystem\Factory\AliyunChunkStorageMacroFactory::class,
    ],
];
