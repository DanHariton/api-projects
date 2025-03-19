<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity;

use App\Model\Entity\Trait\TIdentifierUUID;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class TIdentifierUUIDTest extends TestCase
{
    public function testGetIdReturnsValidUuid(): void
    {
        $mock = new class {
            use TIdentifierUUID;
        };

        $id = $mock->getId();
        $this->assertNotEmpty($id, "Generated UUID should not be empty.");
        $this->assertTrue(Uuid::isValid($id), "Generated ID is not a valid UUID: $id");
    }
}
