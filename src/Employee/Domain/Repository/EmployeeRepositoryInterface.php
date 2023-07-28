<?php

declare(strict_types=1);

namespace App\Employee\Domain\Repository;

use App\Employee\Domain\Entity\Employee;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface EmployeeRepositoryInterface extends PasswordUpgraderInterface
{

}