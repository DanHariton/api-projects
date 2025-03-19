<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Model\Entity\Project;
use App\Service\BaseService;

final class ProjectService extends BaseService
{
    public function save(Project $project): void
    {
        $this->performSave($project);
    }

    public function remove(Project $project): void
    {
        $this->performDelete($project);
    }
}
