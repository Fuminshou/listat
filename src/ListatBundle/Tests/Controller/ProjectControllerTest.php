<?php

namespace ListatBundle\Tests\Controller;

use ListatBundle\Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(4, $crawler->filter('tr')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(3, $crawler->filter('select')->count());
        $this->assertEquals(2, $crawler->filter('button')->count());

        $form = $crawler->selectButton('Create Project')->form();

        $form['project[name]'] = 'Summer Project';
        $form['project[startDate]']['day']->select(21);
        $form['project[startDate]']['month']->select(6);
        $form['project[startDate]']['year']->select(2015);

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(5, $crawler->filter('tr')->count());
        $this->assertContains('Summer Project', $crawler->filter('tr')->last()->text());

        $link = $crawler->filter('a:contains("Summer Project")')->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Summer Project', $crawler->filter('h2')->text());
    }
}
