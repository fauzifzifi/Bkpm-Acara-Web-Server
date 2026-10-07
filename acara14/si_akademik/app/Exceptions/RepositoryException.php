<?php
namespace App\Exceptions;

/**
 * Induk semua error dari lapisan Repository.
 * Controller cukup menangkap exception ini tanpa perlu tahu soal PDO / MySQL.
 */
class RepositoryException extends \RuntimeException
{
}
