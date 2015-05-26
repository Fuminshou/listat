<?php

namespace ListatBundle\Tests\Controller;

use ListatBundle\Tests\TestCase;

class TaskControllerTest extends TestCase
{
    public function testTasklist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $link = $crawler->filter('a:contains("Project 1")')->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h1')->count());
/*        $this->assertEquals(3, $crawler->filter('ul > li')->count());*/
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(3, $crawler->filter('select')->count());
        $this->assertEquals(2, $crawler->filter('button')->count());

        $form = $crawler->selectButton('Create Task')->form();

        $form['task[name]'] = 'Summer Task';
        $form['task[startDate]']['day']->select(21);
        $form['task[startDate]']['month']->select(6);
        $form['task[startDate]']['year']->select(2015);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(4, $crawler->filter('ul > li')->count());
        $this->assertContains('Summer Task', $crawler->filter('ul > li')->last()->text());
    }
}
