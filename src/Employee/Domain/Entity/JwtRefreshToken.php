<?php

namespace App\Employee\Domain\Entity;

use App\Employee\Infrastructure\Repository\JwtRefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

#[ORM\Entity(repositoryClass: JwtRefreshTokenRepository::class)]
#[ORM\Table('`employee_jwt_refresh_token`')]
class JwtRefreshToken extends RefreshToken
{
}
