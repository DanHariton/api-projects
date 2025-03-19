<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\Project;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Model\Entity\Project;
use App\Service\Project\ProjectService;

final readonly class UpdateProjectResolver implements MutationResolverInterface
{
    public function __construct(private ProjectService $service)
    {
    }

    public function __invoke(?object $item, array $context): ?object
    {
        if (!$item instanceof Project) {
            throw new \InvalidArgumentException('Invalid project entity.');
        }

        $args = $context['args']['input'] ?? [];
        $name = $args['name'] ?? null;
        $description = $args['description'] ?? null;

        if ($name) {
            $item->setName($name);
        }
        if ($description) {
            $item->setDescription($description);
        }

        $this->service->save($item);

        return $item;
    }
}
