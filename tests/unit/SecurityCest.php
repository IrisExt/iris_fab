<?php
//declare(strict_types=1);
//
//namespace App\Tests;
//
//use App\Entity\User;
//use App\Tests\UnitTester;
//use Codeception\Util\HttpCode;
//
///**
// * Class SecurityCest
// * @package App\Tests
// */
//class SecurityCest
//{
//    /**
//     * @param \App\Tests\UnitTester $I
//     */
//    public function _before(UnitTester $I)
//    {
//
//    }
//
//    /**
//     * @param \App\Tests\ApiTester $I
//     */
//    public function unauthenticatedTest(UnitTester $I)
//    {
//        $I->sendGET('/fr/');
//        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED); // 401
//    }
//
//    /**
//     * @param \App\Tests\UnitTester $I
//     */
//    public function authenticatedWithBadTokenTest(UnitTester $I)
//    {
//        $I->have(User::class);
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('X-AUTH-TOKEN','UP+pEEhLeoCblXil5fExQ8WPJHizaF1eJzRDX30Ky1/py5HU/MFFfNUkepUyt1/dmVg=');
//        $I->sendGET('/dashboard');
//        $I->seeResponseCodeIs(HttpCode::FORBIDDEN); // 403
//        $I->seeResponseContainsJson([
//            'message' => 'Username could not be found.'
//        ]);
//    }
//
//    /**
//     * @param \App\Tests\UnitTester $I
//     */
//    public function authenticatedWithValidTokenTest(UnitTester $I)
//    {
//        $I->have(User::class);
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->haveHttpHeader('X-AUTH-TOKEN','bGbc7USaEgkTYpXyb6Pi8KDiaLeW7TyPUGhND99qhv2PQiwgSBUWagVzzbxnrzQRsYQ#');
//        $I->sendGET('/dashboard');
//        $I->seeResponseCodeIs(HttpCode::OK); // 200
//        $I->seeResponseContainsJson([
//            'dashboard page test'
//        ]);
//    }
//
//    /**
//     * @param \App\Tests\UnitTester $I
//     */
//    public function loginViaAPI(UnitTester $I)
//    {
//        $I->have(User::class);
//        $I->amHttpAuthenticated('dali@ayruu.fr', 'admin');
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->sendPOST('/login', ['email' => 'dali@ayruu.fr', 'password' => 'admin']);
//        $I->seeResponseCodeIs(HttpCode::OK); // 200
//        $I->seeResponseContainsJson([
//            'lastName' => 'chikhaoui',
//            'email' => 'dali@ayruu.fr',
//            'firstName' => 'mohamed ali'
//        ]);
//    }
//
//    /**
//     * @param \App\Tests\UnitTester $I
//     */
//    public function loginFealedViaAPI(UnitTester $I)
//    {
//        $I->have(User::class);
//        $I->amHttpAuthenticated('incorrect@ayruu.fr', 'admin');
//        $I->haveHttpHeader('Content-Type', 'application/json');
//        $I->sendPOST('/login', ['email' => 'incorrect@ayruu.fr', 'password' => 'admin']);
//        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
//        $I->seeResponseContainsJson([
//            'message' => 'Wrong username or password.'
//        ]);
//    }
//
//}
