<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends ServiceEntityRepository
{
    private mixed $_em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);

        $this->_em = $this->getEntityManager();
    }

    // Add custom methods for querying the Project entity as needed

    public function add(array $projectData, bool $flush = false): void
    {
        $entity = new Project();
        $entity->setName($projectData['name']);
        $entity->setDescription($projectData['description']);
        $entity->setCreatedAt(new \DateTimeImmutable($projectData['createdAt']->format('Y-m-d H:i:s')));
        $entity->setUpdatedAt(new \DateTimeImmutable($projectData['updatedAt']->format('Y-m-d H:i:s')));

        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
