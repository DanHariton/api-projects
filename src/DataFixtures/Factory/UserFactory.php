<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Model\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'name'     => self::faker()->firstName(),
            'password' => self::faker()->sha256(),
            'roles'    => ['ROLE_USER'],
            'login'    => self::faker()->unique()->word(),
        ];
    }
}
