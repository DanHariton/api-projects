<?php

declare(strict_types=1);

namespace App\Security\Voter\Permission;

final class ProjectPermission
{
    public const string EDIT = 'PROJECT_EDIT';
    public const string DELETE = 'PROJECT_DELETE';
}