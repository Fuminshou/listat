<?php

namespace ListatBundle\Tests\Controller;

use ListatBundle\Tests\TestCase;

class TaskControllerTest extends TestCase
{
    public function testTaskList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h2')->count());
        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(4, $crawler->filter('tr')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(3, $crawler->filter('select')->count());
        $this->assertEquals(1, $crawler->filter('button')->count());
        $this->assertEquals(2, $crawler->filter('tr:nth-child(2) > td.col-md-1:first-child > a')->count());

        $link = $crawler->filter('tr:nth-child(4) > td.col-md-1:first-child > a')->first()->link();
        $crawler = $client->click($link);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('tr')->count());

        $form = $crawler->selectButton('Save Task')->form();

        $form['task[name]'] = 'Summer Task';
        $form['task[startDate]']['day']->select(21);
        $form['task[startDate]']['month']->select(6);
        $form['task[startDate]']['year']->select(2014);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(4, $crawler->filter('tr')->count());
        $this->assertContains('Summer Task', $crawler->filter('tr')->last()->text());

        $link = $crawler->filter('tr:nth-child(4) > td.col-md-1:first-child > a')->last()->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(3, $crawler->filter('select')->count());
        $this->assertEquals(1, $crawler->filter('button')->count());

        $form = $crawler->selectButton('Save Task')->form();

        $form['task[name]'] = 'Task 3';
        $form['task[startDate]']['day']->select(5);
        $form['task[startDate]']['month']->select(5);
        $form['task[startDate]']['year']->select(2014);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
