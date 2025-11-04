<?php

namespace App\Exceptions;

use Exception;

class WorkflowException extends Exception
{
    //
}

class InvalidStatusTransitionException extends WorkflowException
{
    public function __construct(string $currentStatus, string $targetStatus)
    {
        parent::__construct("Transisi status dari '{$currentStatus}' ke '{$targetStatus}' tidak valid.");
    }
}

class MissingRequiredDocumentsException extends WorkflowException
{
    public function __construct(array $missingDocuments)
    {
        $documents = implode(', ', $missingDocuments);
        parent::__construct("Dokumen wajib belum diupload: {$documents}");
    }
}

class UnauthorizedActionException extends WorkflowException
{
    public function __construct(string $action, string $status)
    {
        parent::__construct("Aksi '{$action}' tidak diizinkan untuk status '{$status}'.");
    }
}

class DocumentUploadException extends WorkflowException
{
    public function __construct(string $message = "Terjadi kesalahan saat upload dokumen")
    {
        parent::__construct($message);
    }
}
