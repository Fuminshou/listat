<?php

namespace ListatBundle\Tests\Controller;

use ListatBundle\Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    public function testProjectList()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/project/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h2')->count());
        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(4, $crawler->filter('tr')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(3, $crawler->filter('select')->count());
        $this->assertEquals(1, $crawler->filter('button')->count());
        $this->assertEquals(2, $crawler->filter('tr:nth-child(2) > td.col-md-1:first-child > a')->count());

        $link = $crawler->filter('tr:nth-child(2) > td.col-md-1:first-child > a')->first()->link();
        $crawler = $client->click($link);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(3, $crawler->filter('tr')->count());

        $form = $crawler->selectButton('Save Project')->form();

        $form['project[name]'] = 'Project 4';
        $form['project[startDate]']['day']->select(20);
        $form['project[startDate]']['month']->select(5);
        $form['project[startDate]']['year']->select(2014);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(4, $crawler->filter('tr')->count());
        $this->assertContains('Project 4', $crawler->filter('tr')->last()->text());

        $link = $crawler->filter('tr:nth-child(2) > td.col-md-1:first-child > a')->last()->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(3, $crawler->filter('select')->count());
        $this->assertEquals(1, $crawler->filter('button')->count());

        $form = $crawler->selectButton('Save Project')->form();

        $form['project[name]'] = 'Summer Project';
        $form['project[startDate]']['day']->select(21);
        $form['project[startDate]']['month']->select(6);
        $form['project[startDate]']['year']->select(2014);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->filter('a:contains("Project")')->first()->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
