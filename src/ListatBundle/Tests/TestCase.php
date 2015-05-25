<?php

namespace ListatBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ListatBundle\Entity\Project;

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

        $connection->executeUpdate($platform->getTruncateTableSQL('project', true));


        for($i=1; $i<4; $i++) {
            $p = new Project();
            $p->setName("Project ".$i);
            $p->setStartDate(new \DateTime($i.'-'.$i.'-2015'));

            $this->em->persist($p);
            $this->em->flush();
        }
    }
}