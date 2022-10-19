<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Filesystem\Factory;

use AlphaSnow\Flysystem\Aliyun\AliyunException;
use AlphaSnow\LaravelFilesystem\Aliyun\OssClientAdapter;
use Closure;
use Illuminate\Filesystem\FilesystemAdapter;
use OSS\Core\OssException;
use OSS\OssClient;
use Pandawa\ChunkUpload\Filesystem\ChunkStorageMacroFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author  Aldi Arief <aldiarief598@gmail.com>
 */
final class AliyunChunkStorageMacroFactory implements ChunkStorageMacroFactoryInterface
{
    public function uploadPart(): Closure
    {
        return function (string $uploadId, string $path, int $partNumber, File $file, array $options = []) {
            /**
             * @var FilesystemAdapter $this
             */
            $adapter = new OssClientAdapter($this);

            $options = $adapter->options($options);

            $options[OssClient::OSS_PART_NUM] = $partNumber;
            $options[OssClient::OSS_FILE_UPLOAD] = $file;

            try {
                $chunkReferenceId =  $adapter->client()->uploadPart(
                    $adapter->bucket(),
                    $adapter->path($path),
                    $uploadId,
                    $options
                );

                if (str_starts_with($chunkReferenceId, '"')
                    && str_ends_with($chunkReferenceId, '"')) {
                    $chunkReferenceId = substr($chunkReferenceId, 1, strlen($chunkReferenceId) - 2);
                }

                return $chunkReferenceId;
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }

    public function initiateMultipartUpload(): Closure
    {
        return function (string $path, array $options = []) {
            /**
             * @var FilesystemAdapter $this
             */
            $adapter = new OssClientAdapter($this);

            try {
                return $adapter->client()->initiateMultipartUpload(
                    $adapter->bucket(),
                    $adapter->path($path),
                    $adapter->options($options)
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }

    public function completeMultipartUpload(): Closure
    {
        return function (string $uploadId, string $path, array $parts, array $options = []) {
            /**
             * @var FilesystemAdapter $this
             */
            $adapter = new OssClientAdapter($this);

            $chunkParts = [];
            $count = 0;

            foreach ($parts as $part) {
                $chunkParts[] = [
                    'PartNumber' => ($count + 1),
                    'ETag'       => $part,
                ];

                $count += 1;
            }

            try {
                return $adapter->client()->completeMultipartUpload(
                    $adapter->bucket(),
                    $adapter->path($path),
                    $uploadId,
                    $chunkParts,
                    $adapter->options($options)
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }

    public function abortMultipartUpload(): Closure
    {
        return function (string $uploadId, string $path, array $options = []) {
            /**
             * @var FilesystemAdapter $this
             */
            $adapter = new OssClientAdapter($this);

            try {
                return $adapter->client()->abortMultipartUpload(
                    $adapter->bucket(),
                    $adapter->path($path),
                    $uploadId,
                    $adapter->options($options)
                );
            } catch (OssException $exception) {
                throw new AliyunException($exception->getErrorMessage(), 0, $exception);
            }
        };
    }
}