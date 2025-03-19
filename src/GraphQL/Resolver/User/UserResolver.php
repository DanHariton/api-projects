<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\User;

use ApiPlatform\GraphQl\Resolver\QueryItemResolverInterface;
use App\Model\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class UserResolver implements QueryItemResolverInterface
{
    public function __construct(private Security $security) {}

    public function __invoke(?object $item, array $context): object
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException("Unauthorized.");
        }

        return $user;
    }
}
