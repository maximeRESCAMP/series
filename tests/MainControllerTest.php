<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "Serie's detail");
    }
    public function testCreateSerieIsWorkingIfNotlogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/add');

        $this->assertResponseRedirects('/login',302);

    }

    public function testCreateSerieIsWorkingIflogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/add');
        $client->loginUser();
        $this->assertResponseRedirects('/login',302);

    }

}
