<?php

use Codeception\Util\HttpCode;
use App\Tests\AcceptanceTester;

/**
 * Class SecurityCest
 * @package App\Tests
 */
class AppelProjetCest
{
    // tests
    public function testSomeFeature()
    {

    }

    /**
     * @param UnitTester $I
     */
    public function testCreateAppelProjet(AcceptanceTester $I): void
    {
//        $uri = '/api/fr/corporate/b8f96aec-c7bc-4530-82d0-d02b84ba8c99/feeds';
//        $I->wantTo('create feed');
//        $I->sendPOST(
//            $uri,
//            [
//                "description" => "Phrase de test !",
//            ]
//        );
//        $I->seeResponseCodeIs(HttpCode::CREATED); //201
//        $I->seeResponseContains('b8f96aec-c7bc-4530-82d0-d02b84ba8c99');
    }

    public function testGetAllAppelFr(AcceptanceTester $I): void
    {
        $I->wantTo('Get appels list Fr');
        $I->amOnPage('/fr/login/');
        $I->seeInCurrentUrl('/fr/login');
        $I->dontSee('Bad credentials');
        $I->fillField('_username', 'dosem1');
        $I->fillField('_password', 'anr19');
        $I->click('_submit');
        $I->amOnPage('/fr/appel');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->see('Appel à Projets');
        $I->see('AAPG-2021');
    }

    public function testGetAllAppelEn(AcceptanceTester $I): void
    {
        $I->wantTo('Get appels list En');
        $I->amOnPage('/fr/login/');
        $I->seeInCurrentUrl('/fr/login');
        $I->dontSee('Bad credentials');
        $I->fillField('_username', 'dosem1');
        $I->fillField('_password', 'anr19');
        $I->click('_submit');
        $I->amOnPage('/en/appel');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->see('call for project');
    }

    public function testGetAppel(AcceptanceTester $I): void
    {
        $I->wantTo('Get appel');
        $I->amOnPage('/fr/login/');
        $I->seeInCurrentUrl('/fr/login');
        $I->dontSee('Bad credentials');
        $I->fillField('_username', 'dosem1');
        $I->fillField('_password', 'anr19');
        $I->click('_submit');
        $I->amOnPage('/fr/appel/6');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->see('Millesime');
        $I->see('Libellé');
        $I->see('Acronyme');
        $I->see('Pilote');
        $I->see('2021');
        $I->see('Appel à projet 2021');
        $I->see('AAPG-2021');
    }

}