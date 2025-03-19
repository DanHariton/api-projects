<?php

namespace App\Model\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use App\GraphQL\Resolver\User\LoginResolver;
use App\GraphQL\Resolver\User\SignupResolver;
use App\GraphQL\Resolver\User\UserResolver;
use App\Model\Entity\Trait\TIdentifierUUID;
use App\Model\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ApiResource(
    graphQlOperations: [
        new Query(name: "item_query"),
        new Query(
            resolver: UserResolver::class,
            args: [],
            normalizationContext: ['groups' => ['user:read', 'user:projects']],
            security: "is_granted('ROLE_USER')", name: 'me'
        ),
        new Mutation(
            resolver: SignupResolver::class,
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            name: 'signup'
        ),
        new Mutation(
            resolver: LoginResolver::class,
            normalizationContext: ['groups' => ['user:read']],
            name: 'login'
        ),
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TIdentifierUUID;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column(type: Types::STRING, unique: true, nullable: false)]
    #[Assert\NotBlank]
    private ?string $login = null;

    /**
     * @var list<string> The user roles
     */
    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: false)]
    private array $roles = [];

    #[Groups(['user:write'])]
    #[ORM\Column(nullable: false)]
    private ?string $password = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Project>
     */
    #[Groups(['user:read'])]
    #[ApiProperty(fetchEager: true)]
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'owner')]
    private Collection $projects;

    #[Groups(['user:read'])]
    private ?string $token = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getOwner() === $this) {
                $project->setOwner(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): User
    {
        $this->token = $token;
        return $this;
    }
}
