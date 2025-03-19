<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\User;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Service\User\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class LoginResolver implements MutationResolverInterface
{
    public function __construct(
        private UserService              $userService,
        private UserRepository           $userRepository,
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

        $user = $this->userRepository->getUserByLogin($login);
        if (!$user) {
            throw new AccessDeniedException('User not found');
        }

        if (!$this->userService->isValidPassword($user, $plainPassword)) {
            throw new AccessDeniedException('Invalid password');
        }

        $token = $this->jwtManager->create($user);
        $user->setToken($token);

        return $user;
    }
}
