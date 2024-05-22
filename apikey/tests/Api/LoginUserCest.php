<?php


namespace Tests\Api;

use Tests\Support\ApiTester;

class LoginUserCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }

    // tests
    public function LoginUserViaAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPostAsJson('app1/login', [
          'password' => 'password',
          'email' => 'test@example.com'
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(['token' => 'string']);
    }

    public function LoginUnauthorizedUserViaAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPostAsJson('app1/login', [
          'password' => 'password1',
          'email' => 'test@example.com'
        ]);
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(["error" => "string"]);

    }
}
