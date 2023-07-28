<?php

namespace App\Employee\Infrastructure\Repository;

use App\Employee\Domain\Repository\JwtRefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;


class JwtRefreshTokenRepository extends RefreshTokenRepository implements JwtRefreshTokenRepositoryInterface
{

}
