<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload;

use Illuminate\Http\Request;
use RuntimeException;

/**
 * @method string getIdentifier(Request $request)
 * @method int getPart(Request $request)
 * @method int getTotalChunks(Request $request)
 * @method int getChunkSize(Request $request)
 * @method int getTotalSize(Request $request)
 * @method string getFileName(Request $request)
 * @method bool isValidRequest(Request $request)
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

    public function __call($method, ...$arguments)
    {
        return $this->handler()->{$method}(...$arguments);
    }
}
