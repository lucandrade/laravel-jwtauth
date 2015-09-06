<?php

namespace Tests;

use \Tests\BaseTest as BaseTest;
use Lucandrade\JwtAuth\Storage\SessionStorage;
use Firebase\JWT\JWT;
use \JwtAuth;

/**
 * Credentials Test
 * @author     Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class ApiResponseTest extends BaseTest
{

    protected $class;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate', [
            '--database' => 'default',
            '--realpath' => realpath(__DIR__.'/../database/migrations/')
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            '\Lucandrade\JwtAuth\JwtAuthServiceProvider',
        ];
    }

    protected function getPackageAliases($app)
    {
        return array(
            'JwtAuth' => '\Lucandrade\JwtAuth\Facades\JwtAuth'
        );
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'default');
        $app['config']->set('database.connections.default', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function testPayload()
    {
        $payload = "adssdf";
        JwtAuth::setPayload($payload);
        $this->assertEquals($payload, JwtAuth::getPayload());
    }

    public function testConfig()
    {
        $this->assertTrue(is_array(JwtAuth::getConfig()));
    }

    public function testCreateToken()
    {
        $data = ["user" => ["asas"]];
        $userId = 1;
        $token = JwtAuth::createToken($userId, $data);
        $this->assertFalse(empty($token));
    }
}
