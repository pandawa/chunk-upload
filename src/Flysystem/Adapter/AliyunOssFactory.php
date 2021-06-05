<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem\Adapter;

use OSS\OssClient;
use Pandawa\ChunkUpload\Flysystem\ChunkStorage;
use Pandawa\ChunkUpload\Flysystem\ChunkStorageFactoryAdapter;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class AliyunOssFactory implements ChunkStorageFactoryAdapter
{
    public function create(array $config): ChunkStorage
    {
        return new AliyunOssChunkAdapter(
            $this->createClient($config),
            $config['bucket'],
            $config['prefix'] ?? null
        );
    }

    private function createClient(array $config): OssClient
    {
        return new OssClient(
            $config['access_id'],
            $config['access_key'],
            $config['endpoint']
        );
    }
}
