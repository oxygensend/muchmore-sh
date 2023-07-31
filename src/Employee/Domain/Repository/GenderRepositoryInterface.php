<?php

declare(strict_types=1);

namespace App\Employee\Domain\Repository;

use App\Employee\Domain\Entity\Gender;

/**
 *
 * @method Gender|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gender|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gender[]    findAll()
 * @method Gender[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface GenderRepositoryInterface
{

    public function getGenderOrFail(int $id): Gender;


}