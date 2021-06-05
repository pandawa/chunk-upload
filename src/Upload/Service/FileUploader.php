<?php

declare(strict_types=1);

namespace Pandawa\ChunkUpload\Upload\Service;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pandawa\ChunkUpload\Upload\Contract\File;
use Pandawa\ChunkUpload\Upload\Contract\FileRepository;
use Pandawa\ChunkUpload\Upload\DTO\Chunked;
use Pandawa\ChunkUpload\Upload\Exception\UploadFileException;
use Pandawa\ChunkUpload\Upload\UploadManager;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class FileUploader
{
    private UploadManager $uploadManager;
    private FileRepository $fileRepository;
    private FilesystemManager $storage;

    public function __construct(UploadManager $uploadManager, FileRepository $fileRepository, FilesystemManager $storage)
    {
        $this->uploadManager = $uploadManager;
        $this->fileRepository = $fileRepository;
        $this->storage = $storage;
    }

    public function upload(string $driver, Request $request, ?UploadedFile $uploadedFile): Chunked
    {
        $this->validate($request, $uploadedFile);

        $fileIdentifier = $this->uploadManager->getIdentifier($request);
        $file = $this->findFile($fileIdentifier);
        $chunkPart = $this->uploadManager->getPart($request);

        if ($request->isMethod('GET')) {
            return new Chunked($file, $chunkPart);
        }

        return new Chunked(
            $file,
            $chunkPart,
            $this->storage->drive($driver)->uploadPart(
                $file->getReference(),
                $file->getPath(),
                $chunkPart,
                $uploadedFile
            ),
            $this->uploadManager->getTotalChunks($request),
            true
        );
    }

    private function validate(Request $request, ?UploadedFile $uploadedFile): void
    {
        if ($request->isMethod('POST') && null === $uploadedFile) {
            throw new UploadFileException('Missing file.', 400);
        }

        if (!$this->uploadManager->isValidRequest($request)) {
            throw new UploadFileException('Invalid chunk upload request.', 400);
        }
    }

    private function findFile(string $fileIdentifier): File
    {
        if (null !== $file = $this->fileRepository->findByIdentifier($fileIdentifier)) {
            return $file;
        }

        throw new UploadException(sprintf('File with identifier "%s" is not found.', $fileIdentifier), 404);
    }
}
