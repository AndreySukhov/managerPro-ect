<?php

namespace tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\DataFixtures\ORM\Load_01_UserData;
use AppBundle\DataFixtures\ORM\Load_02_ProjectData;

class ProjectControllerTest extends WebTestCase
{
    public static $fixtures = [
        Load_01_UserData::class,
        Load_02_ProjectData::class,
    ];

    public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals($statusCode, $response->getStatusCode(), $response->getContent());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testAllAction()
    {
        $this->loadFixtures(self::$fixtures);
        $members = Load_02_ProjectData::$members;

        $this->client->request('GET', $this->getUrl('app_get_projects'), ['ACCEPT' => 'application/json']);

        $response = $this->client->getResponse();
        $content = $response->getContent();

        $this->assertJsonResponse($response, 200);
        $this->assertNotNull($content);
    }

    public function testGetAction()
    {
        $fixtures = [Load_01_UserData::class, Load_02_ProjectData::class];

        $this->loadFixtures($fixtures);
        $members = Load_02_ProjectData::$members;

        $this->client->request('GET', $this->getUrl('app_get_project', ['project' => $members[0]->getId()]), ['ACCEPT' => 'application/json']);

        $response = $this->client->getResponse();
        $content = $response->getContent();

        $this->assertJsonResponse($response, 200);
        $this->assertNotNull($content);
    }

    public function testPostAction()
    {
        $this->markTestSkipped('Пылесос');

        $fixtures = [Load_01_UserData::class, Load_02_ProjectData::class];

        $this->loadFixtures($fixtures);
        $members = Load_02_ProjectData::$members;

        $this->client->request('POST', $this->getUrl('app_get_projects'), ['ACCEPT' => 'application/json'], [], [], json_encode([
            'project' => [
                'branch' => 'master',
                'name' => 'Test',
                'source' => './',
            ],
        ]));

        $response = $this->client->getResponse();
        $content = $response->getContent();

        $this->assertJsonResponse($response, 200);
        // $this->assertEquals(count($members) + 1, json_decode($con, true));
    }


}
