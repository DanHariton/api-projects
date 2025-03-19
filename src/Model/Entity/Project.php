<?php

namespace App\Model\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use App\GraphQL\Resolver\Project\CreateProjectResolver;
use App\GraphQL\Resolver\Project\DeleteProjectResolver;
use App\GraphQL\Resolver\Project\UpdateProjectResolver;
use App\Model\Entity\Trait\TIdentifierUUID;
use App\Model\Repository\ProjectRepository;
use App\Security\Voter\Permission\ProjectPermission;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    graphQlOperations: [
        new Query(
            normalizationContext: ['groups' => ['project:read']],
            name: "item_query"
        ),
        new Query(
            normalizationContext: ['groups' => ['project:read']],
            security: "is_granted(" . ProjectPermission::EDIT . ", object)",
            name: "project"
        ),
        new Mutation(
            resolver: CreateProjectResolver::class,
            denormalizationContext: ['groups' => ['project:write']],
            security: "is_granted('ROLE_USER')",
            name: "createProject"
        ),
        new Mutation(
            resolver: UpdateProjectResolver::class,
            denormalizationContext: ['groups' => ['project:write']],
            security: "is_granted(" . ProjectPermission::EDIT . ", object)",
            name: "updateProject"
        ),
        new Mutation(
            resolver: DeleteProjectResolver::class,
            security: "is_granted(" . ProjectPermission::DELETE . ", object)",
            name: "deleteProject"
        ),
    ]
)]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    use TIdentifierUUID;

    #[Groups(['user:read', 'project:read', 'project:write'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[Groups(['user:read', 'project:read', 'project:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(['project:read'])]
    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
