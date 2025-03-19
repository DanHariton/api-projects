<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Model\Entity\Project;
use App\Model\Entity\User;
use App\Security\Voter\Permission\ProjectPermission;
use App\Security\Voter\ProjectVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectVoterTest extends TestCase
{
    private ProjectVoter $voter;
    private TokenInterface $token;
    private User $admin;
    private User $owner;
    private User $otherUser;
    private Project $project;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->voter = new ProjectVoter($this->security);
        $this->token = $this->createMock(TokenInterface::class);

        $this->admin = new User();
        $this->admin->setRoles(['ROLE_ADMIN']);

        $this->owner = new User();
        $this->owner->setRoles(['ROLE_USER']);

        $this->otherUser = new User();
        $this->otherUser->setRoles(['ROLE_USER']);

        $this->project = new Project();
        $this->project->setOwner($this->owner);
    }

    public function testAdminCanEditAndDelete(): void
    {
        $this->token->method('getUser')->willReturn($this->admin);
        $this->security->method('isGranted')->with('ROLE_ADMIN')->willReturn(true);

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $this->project, [ProjectPermission::EDIT])
        );

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $this->project, [ProjectPermission::DELETE])
        );
    }

    public function testOwnerCanEditAndDelete(): void
    {
        $this->token->method('getUser')->willReturn($this->owner);
        $this->security->method('isGranted')->with('ROLE_ADMIN')->willReturn(false);

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $this->project, [ProjectPermission::EDIT])
        );

        $this->assertSame(
            VoterInterface::ACCESS_GRANTED,
            $this->voter->vote($this->token, $this->project, [ProjectPermission::DELETE])
        );
    }

    public function testOtherUserCannotEditOrDelete(): void
    {
        $this->token->method('getUser')->willReturn($this->otherUser);
        $this->security->method('isGranted')->with('ROLE_ADMIN')->willReturn(false);

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $this->project, [ProjectPermission::EDIT])
        );

        $this->assertSame(
            VoterInterface::ACCESS_DENIED,
            $this->voter->vote($this->token, $this->project, [ProjectPermission::DELETE])
        );
    }
}