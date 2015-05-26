<?php

namespace ListatBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ListatBundle\Entity\Project;
use ListatBundle\Entity\Task;

class TestCase extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        //truncate
        $connection = $this->em->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('task', true));
        $connection->executeUpdate('DELETE FROM project WHERE id >= 1');

        $projects = array();
        for($i=1; $i<=3; $i++) {
            $p = new Project();
            $p->setName("Project ".$i);
            $p->setStartDate(new \DateTime($i.'-'.$i.'-2015'));

            $projects[] = $p;

            $this->em->persist($p);
            $this->em->flush();
        }

        for($j=1; $j<=3; $j++) {
            $t = new Task();
            $t->setName("Task ".$j);
            $t->setStartDate(new \DateTime($j.'-'.$j.'-2015'));
            $t->setProject($projects[0]);

            $this->em->persist($t);
            $this->em->flush();
        }
    }
}