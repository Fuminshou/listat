<?php

namespace ListatBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ListatBundle\Entity\Project;
use ListatBundle\Entity\Task;
use ListatBundle\Entity\User;

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
        $connection->executeUpdate('ALTER TABLE project AUTO_INCREMENT = 1');
        $connection->executeUpdate('DELETE FROM user WHERE id >= 1');
        $connection->executeUpdate('ALTER TABLE user AUTO_INCREMENT = 1');

        //create 3 user
        $users = array();
        for($h=1; $h<=3; $h++) {
            $u = new User();
            $u->setUsername("Name ".$h);
            $u->setEmail('name'.$h.'@example.com');
            $u->setPassword('password'.$h);

            $users[] = $u;

            $this->em->persist($u);
            $this->em->flush();
        }

        //create 3 project, assigned to user 1
        $projects = array();
        for($i=1; $i<=3; $i++) {
            $p = new Project();
            $p->setName("Project ".$i);
            $p->setStartDate(new \DateTime($i.'-'.$i.'-2014'));
            $p->setUser($users[0]);

            $projects[] = $p;

            $this->em->persist($p);
            $this->em->flush();
        }

        //create 3 project, assigned to user 2
        $projectsU2 = array();
        for($i=1; $i<=3; $i++) {
            $p = new Project();
            $p->setName("Project ".$i);
            $p->setStartDate(new \DateTime($i.'-'.$i.'-2013'));
            $p->setUser($users[1]);

            $projectsU2[] = $p;

            $this->em->persist($p);
            $this->em->flush();
        }

        //create 3 task, assigned project #1 (user 1)
        for($j=1; $j<=3; $j++) {
            $t = new Task();
            $t->setName("Task ".$j);
            $t->setStartDate(new \DateTime($j.'-'.$j.'-2014'));
            $t->setLastUpdate(new \DateTime(($j+2) .'-'. ($j+2) .'-2014'));
            $t->setProject($projects[0]);

            $this->em->persist($t);
            $this->em->flush();
        }

        //create 3 task, assigned project #2 (user 1)
        for($j=1; $j<=3; $j++) {
            $t = new Task();
            $t->setName("Task ".$j);
            $t->setStartDate(new \DateTime($j.'-'.$j.'-2013'));
            $t->setLastUpdate(new \DateTime(($j+2) .'-'. ($j+2) .'-2013'));
            $t->setProject($projects[1]);

            $this->em->persist($t);
            $this->em->flush();
        }
    }
}