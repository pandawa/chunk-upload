<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pandawa\ChunkUpload\Upload\Contract\ChunkUploadPayload;
use Pandawa\ChunkUpload\Upload\Exception\UploadFileException;
use RuntimeException;

/**
 * @mixin UploadHandler
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class UploadManager
{
    /**
     * @var UploadHandler[]
     */
    private array $handlers = [];

    private string $defaultHandler;

    public function __construct(string $defaultHandler, $handlers)
    {
        $this->defaultHandler = $defaultHandler;

        foreach ($handlers as $handler) {
            $this->add($handler);
        }
    }

    public function add(UploadHandler $handler): void
    {
        $this->handlers[$handler->name()] = $handler;
    }

    public function handler(?string $name = null): UploadHandler
    {
        $handler = $name ?? $this->defaultHandler;

        if (!array_key_exists($handler, $this->handlers)) {
            throw new RuntimeException(sprintf('Handler with name "%s" is not found.', $handler));
        }

        return $this->handlers[$handler];
    }

    public function validateAndExtractRequest(Request $request): ChunkUploadPayload
    {
        $this->validateRequest($request);

        return $this->extractRequest($request);
    }

    public function validateRequest(Request $request): void
    {
        if (!$this->isValidRequest($request)) {
            throw new UploadFileException('Invalid chunk upload request.', 400);
        }
    }

    public function extractRequest(Request $request): ChunkUploadPayload
    {
        return $this->getPayload($request);
    }

    public function __call($method, $arguments)
    {
        return $this->handler()->{$method}(...$arguments);
    }
}
