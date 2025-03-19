<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Model\Entity\Project;
use App\Model\Entity\User;
use App\Security\Voter\Permission\ProjectPermission;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ProjectVoter extends Voter implements CacheableVoterInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public const array SUPPORTS = [
        ProjectPermission::EDIT,
        ProjectPermission::DELETE,
    ];

    public function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, self::SUPPORTS, true);
    }

    public function supportsType(string $subjectType): bool
    {
        return $subjectType === Project::class;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $this->supportsAttribute($attribute) && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return match ($attribute) {
            ProjectPermission::EDIT, ProjectPermission::DELETE => $user === $subject->getOwner(),
            default => false,
        };
    }
}
