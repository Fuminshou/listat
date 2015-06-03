<?php

namespace ListatBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function findLastUpdateByProject(Project $project)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQuery('
            SELECT MAX(t.lastUpdate)
            FROM ListatBundle:Task t
            WHERE t.project = :project
        ')->setParameter(':project', $project);

        $lastUpdate = $queryBuilder->getSingleScalarResult();

        if(!$lastUpdate) {
            return null;
        }

        return new \DateTime($lastUpdate);
    }
}