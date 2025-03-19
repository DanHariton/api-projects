<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Model\Entity\User;
use App\Service\BaseService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService extends BaseService
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function save(User $user): void
    {
        $this->performSave($user);
    }

    public function hashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    public function isValidPassword(User $user, string $password): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $password);
    }
}
