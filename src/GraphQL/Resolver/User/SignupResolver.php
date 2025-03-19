<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\User;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Model\Entity\User;
use App\Service\User\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final readonly class SignupResolver implements MutationResolverInterface
{
    public function __construct(
        private UserService              $userService,
        private JWTTokenManagerInterface $jwtManager
    ) {
    }

    public function __invoke(?object $item, array $context): User
    {
        $args = $context['args']['input'] ?? [];
        $login = $args['login'] ?? null;
        $plainPassword = $args['password'] ?? null;

        if (!$login || !$plainPassword) {
            throw new \InvalidArgumentException('Login and Password is required');
        }

        $user = new User();
        $user->setLogin($login);
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->userService->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->userService->save($user);
        $token = $this->jwtManager->create($user);
        $user->setToken($token);

        return $user;
    }
}
