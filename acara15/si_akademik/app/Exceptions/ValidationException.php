<?php
namespace App\Exceptions;

/**
 * Input tidak valid / melanggar aturan bisnis (mis. NIM sudah terdaftar).
 * Pesan di dalamnya AMAN ditampilkan ke pengguna. Berbeda dengan error teknis
 * (database, dst.) yang tidak boleh ditampilkan dan hanya dicatat ke log.
 */
class ValidationException extends \Exception
{
    private array $errors;

    /** @param array $errors ['field' => 'pesan', ...] */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct(implode(' ', array_values($errors)));
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
