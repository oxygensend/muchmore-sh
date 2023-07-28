<?php

declare(strict_types=1);

namespace App\Employee\Domain\Repository;


use App\Employee\Domain\Entity\JwtRefreshToken;

/**
 * @method JwtRefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method JwtRefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method JwtRefreshToken[]    findAll()
 * @method JwtRefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface JwtRefreshTokenRepositoryInterface
{

}