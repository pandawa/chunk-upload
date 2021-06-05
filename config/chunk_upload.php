<?php

return [
    'plugins' => [
        ApolloPY\Flysystem\AliyunOss\Plugins\PutFile::class,
        ApolloPY\Flysystem\AliyunOss\Plugins\SignedDownloadUrl::class,
        Pandawa\ChunkUpload\Flysystem\Plugin\InitiateMultipartUpload::class,
        Pandawa\ChunkUpload\Flysystem\Plugin\UploadPart::class,
        Pandawa\ChunkUpload\Flysystem\Plugin\CompleteMultipartUpload::class,
        Pandawa\ChunkUpload\Flysystem\Plugin\AbortMultipartUpload::class,
    ],

    'upload_handler' => env('CHUNK_UPLOAD_HANDLER', 'resumablejs'),
];
