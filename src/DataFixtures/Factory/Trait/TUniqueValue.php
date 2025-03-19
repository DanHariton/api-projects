<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory\Trait;

/**
 * Provide method to generate unique value from finite set of values.
 * If the value is already used, it will append a number to the value.
 */
trait TUniqueValue
{
    /** @var array<string, array<string, int>> */
    private static array $uniques = [];

    /**
     * @example self::getUniqueValue('project', $faker->ingredient());
     */
    private static function getUniqueValue(string $domain, string $value): string
    {
        if (!isset(self::$uniques[$domain][$value])) {
            self::$uniques[$domain][$value] = 1;
        } else {
            self::$uniques[$domain][$value]++;
        }

        if (self::$uniques[$domain][$value] > 1) {
            return $value . ' ' . self::$uniques[$domain][$value];
        }

        return $value;
    }
}
