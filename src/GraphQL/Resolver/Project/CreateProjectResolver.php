<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver\Project;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Model\Entity\Project;
use App\Model\Entity\User;
use App\Service\Project\ProjectService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final readonly class CreateProjectResolver implements MutationResolverInterface
{
    public function __construct(
        private Security       $security,
        private ProjectService $service
    ) {}

    public function __invoke(?object $item, array $context): Project
    {
        /** @var User|null $user */
        $user = $this->security->getUser();
        if (!$user) {
            throw new AccessDeniedException('You must be logged in to create a project.');
        }

        $args = $context['args']['input'] ?? [];
        $name = $args['name'] ?? null;
        $description = $args['description'] ?? null;

        if (!$name) {
            throw new \InvalidArgumentException('Project name is required.');
        }

        $project = new Project();
        $project->setName($name);
        $project->setDescription($description);
        $project->setOwner($user);

        $this->service->save($project);

        return $project;
    }
}
