<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class BaseService
{
    #[Required]
    public EntityManagerInterface $em;

    protected function performSave(object $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function performDelete(object $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
