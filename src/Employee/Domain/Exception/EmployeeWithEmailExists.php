<?php
declare(strict_types=1);

namespace App\Employee\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class EmployeeWithEmailExists extends \Exception
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf("Employee with email '%s' exists.", $email), Response::HTTP_BAD_REQUEST);
    }
}