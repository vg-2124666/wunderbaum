<?php

    namespace Wunderbaum\Tests;

    use Silex\WebTestCase;

    /**
     * Testcases for the application routes. Only does basic testing to see that the routes loads without
     * errors
     *
     * @package Wunderbaum\Tests
     */
    class RoutesTest extends WebTestCase
    {
        public function createApplication()
        {
            $app = require __DIR__.'/../../../app.php';
            $app['debug'] = true;
            unset($app['exception_handler']);

            return $app;
        }

        /**
         * Test that the frontpage loads with no errors
         */
        public function testHome()
        {
            $client = $this->createClient();
            $crawler = $client->request('GET', '/');
            $content = $client->getResponse()->getContent();

            $this->assertTrue($client->getResponse()->isOk());
            $this->assertContains('Wunderbaum', $content);
        }

        /**
         * Test that the top 5 page loads
         */
        public function testTopFive()
        {
            $client = $this->createClient();
            $crawler = $client->request('GET', '/highfive');
            $content = $client->getResponse()->getContent();

            $this->assertTrue($client->getResponse()->isOk());
            $this->assertContains('Top 5 visited hosts', $content);
            $this->assertContains('Top 5 accessed files', $content);
        }

        /**
         * Test that the rss news page loads
         */
        public function testNewsRss()
        {
            $client = $this->createClient();
            $crawler = $client->request('GET', '/news-rss');
            $content = $client->getResponse()->getContent();

            $this->assertTrue($client->getResponse()->isOk());
            $this->assertContains('class="list-group-item-heading"', $content);
        }

        /**
         * Test that the json news page loads
         */
        public function testNewsJson()
        {
            $client = $this->createClient();
            $crawler = $client->request('GET', '/news-json');
            $content = $client->getResponse()->getContent();

            $this->assertTrue($client->getResponse()->isOk());
            $this->assertContains('class="list-group-item-heading"', $content);
        }

    }
