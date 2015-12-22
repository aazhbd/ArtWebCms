<?php

use ArtLibs\Controller;

class Views extends Controller
{
    public function viewHome($params, $app)
    {
        $app->setTemplateData(
            array(
                'title' => 'Test home page',
                'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
            )
        );
        $this->display($app, 'frm_signup.twig');
    }

    public function viewCustom($params, $app)
    {
        $app->setTemplateData(
            array(
                'title' => 'This is custom page',
                'body_content' => 'A test custom page loaded from controller/view.php.'
            )
        );

        $this->display($app, 'home.twig');
    }

    public function viewLogin($params, $app)
    {
        $app->setTemplateData(
            array(
                'title' => 'Login',
            )
        );

        $this->display($app, 'frm_login.twig');
    }

    public function viewSignup($params, $app)
    {
        $app->setTemplateData(
            array(
                'title' => 'Signup',
            )
        );

        $this->display($app, 'frm_signup.twig');
    }
}
