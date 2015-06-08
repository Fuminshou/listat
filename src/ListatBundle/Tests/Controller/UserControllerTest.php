<?php

namespace ListatBundle\Tests\Controller;

use ListatBundle\Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(1, $crawler->filter('p')->count());
        $this->assertEquals(2, $crawler->filter('a > i')->count());

        $link = $crawler->filter('a:contains("Login")')->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUserRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=email]')->count());
        $this->assertEquals(1, $crawler->filter('input[type=text]')->count());
        $this->assertEquals(2, $crawler->filter('input[type=password]')->count());
        $this->assertEquals(1, $crawler->filter('button')->count());

        $form = $crawler->selectButton('Register')->form();

        $form['userRegistration[email]'] = 'email@example.com';
        $form['userRegistration[username]'] = 'username';
        $form['userRegistration[password][password]'] = 'password';
        $form['userRegistration[password][confirm]'] = 'password';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h2')->count());
    }

    public function testUserLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h3')->count());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('input[type=email]')->count());
        $this->assertEquals(1, $crawler->filter('input[type=password]')->count());
        $this->assertEquals(1, $crawler->filter('button')->count());

        $form = $crawler->selectButton('Login')->form();

        $form['userLogin[email]'] = 'name1@example.com';
        $form['userLogin[password]'] = 'password1';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h2')->count());
    }
}