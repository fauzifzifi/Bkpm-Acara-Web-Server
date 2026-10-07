<?php
namespace App\Exceptions;

/** Data tidak bisa dihapus karena masih dipakai data lain (FOREIGN KEY). */
class InUseException extends RepositoryException
{
}
