<?php
namespace App\Exceptions;

/** Data merujuk ke data lain yang tidak ada (FOREIGN KEY tujuan tidak ditemukan). */
class InvalidReferenceException extends RepositoryException
{
}
