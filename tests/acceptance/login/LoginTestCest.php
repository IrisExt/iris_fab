<?php

use App\Tests\AcceptanceTester;

class LoginTestCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function LoginIsUnsuccessfulWithBadCredentials(AcceptanceTester $i, \Codeception\Scenario $scenario)
    {
        $i->wantTo('Ensure a user is unable to log in when providing bad credentials');

        $i->amOnPage('/fr/login/');
        $i->click('_submit');
        $i->seeInCurrentUrl('/fr/login');
        $i->dontSee('Bad Credentials');
        $i->submitForm('#_submit', [
            'username' => 'adlen',
            'password' => '123',
        ]);
        //$this->doLogin($scenario, 'adlen', '123');
        $i->see('Identifiants invalides.');
    }

    public function LoginIsSuccessfulWithGoodCredentials(AcceptanceTester $i, \Codeception\Scenario $scenario)
    {
        $i->wantTo('Ensure a user can log in when giving valid credentials.');
        $i->amOnPage('/fr/login/');

//        $I->click('Browse backend');
//        $I->seeInCurrentUrl('/en/login');
//        $I->see('Secure Sign in', 'legend');
//        $I->fillField('Username', 'jane_admin');
//        $I->fillField('Password', 'kitten');
//        $I->click('Sign in');
//        $I->seeInCurrentUrl('admin');
//        $I->seeLink('Logout');
//

        $i->seeInCurrentUrl('/fr/login');
        $i->dontSee('Bad credentials');
        $i->fillField('_username', 'adlen.boussadia@agencerecherche.fr');
        $i->fillField('_password', 'anr19');
        $i->click('_submit');
        // $this->doLogin($scenario, 'adlen.boussadia@agencerecherche.fr', 'anr19');
        $i->dontSee('Bad Credentials');
        $i->seeInCurrentUrl('/fr');
        $i->see('Accueil');
    }

    private function doLogin(\Codeception\Scenario $scenario, $username, $password)
    {
        $userLoginStep = new AcceptanceTester\UserLoginSteps($scenario);
        $userLoginStep->doLogin($username, $password);
    }
}
