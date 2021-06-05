<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Flysystem\Adapter;

use ApolloPY\Flysystem\AliyunOss\AliyunOssAdapter;
use Illuminate\Http\UploadedFile;
use League\Flysystem\Config;
use OSS\Core\OssException;
use OSS\OssClient;
use Pandawa\ChunkUpload\Flysystem\ChunkStorage;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class AliyunOssChunkAdapter extends AliyunOssAdapter implements ChunkStorage
{
    public function uploadPart(string $uploadId, string $path, int $partNumber, UploadedFile $file, Config $config): string
    {
        $options = $this->getOptionsFromConfig($config);

        $options[OssClient::OSS_PART_NUM] = $partNumber;
        $options[OssClient::OSS_FILE_UPLOAD] = $file;

        return $this->client->uploadPart(
            $this->bucket,
            $this->applyPathPrefix($path),
            $uploadId,
            $options
        );
    }

    public function initiateMultipartUpload(string $path, Config $config): string
    {
        return $this->client->initiateMultipartUpload(
            $this->bucket,
            $this->applyPathPrefix($path),
            $this->getOptionsFromConfig($config)
        );
    }

    public function completeMultipartUpload(string $uploadId, string $path, array $parts, Config $config): bool
    {
        return (bool)$this->client->completeMultipartUpload(
            $this->bucket,
            $this->applyPathPrefix($path),
            $uploadId,
            $parts,
            $this->getOptionsFromConfig($config)
        );
    }

    public function abortMultipartUpload(string $uploadId, string $path, Config $config): bool
    {
        try {
            $this->client->abortMultipartUpload(
                $this->bucket,
                $this->applyPathPrefix($path),
                $uploadId,
                $this->getOptionsFromConfig($config)
            );
        } catch (OssException $e) {
            return false;
        }

        return true;
    }
}
