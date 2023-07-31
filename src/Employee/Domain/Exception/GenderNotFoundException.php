<?php

declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class GenderNotFoundException extends \Exception
{
    public function __construct(int $id)
    {
        parent::__construct(sprintf("Gender with id %s not found.", $id), Response::HTTP_BAD_REQUEST);
    }

}