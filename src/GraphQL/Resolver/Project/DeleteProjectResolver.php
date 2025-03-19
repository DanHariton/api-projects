<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\Project;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Model\Entity\Project;
use App\Service\Project\ProjectService;

final readonly class DeleteProjectResolver implements MutationResolverInterface
{
    public function __construct(private ProjectService $service)
    {
    }

    public function __invoke(?object $item, array $context): ?object
    {
        if (!$item instanceof Project) {
            throw new \InvalidArgumentException('Invalid project entity.');
        }

        $this->service->remove($item);

        return null;
    }
}
