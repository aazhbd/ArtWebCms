<?php

use ArtLibs\Controller;
use ArtLibs\User;


class Views extends Controller
{
    public function viewHome($params, $app)
    {
        $app->setTemplateData(
            array(
                'title' => 'Home',
                'user_var.project_name' => "Test information"
            )
        );

        if($app->getRequest()->getMethod() == "POST") {
            $email = $app->getRequest()->request->get('email');
            $pass = $app->getRequest()->request->get('password');

            $user = new User($app, $email, $pass);

            if(!$user->isAuthenticated()) {
                $app->setTemplateData(array('content_message' => 'Login unsuccessful, try again.'));
                $this->display($app, 'frm_login.twig');
                return;
            }
            else {
                $app->setTemplateData(array('content_message' => 'Login successful.'));
            }
        }

        $this->display($app, 'uhome.twig');
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
        $app->setTemplateData(array( 'title' => 'Login', ));

        if($app->getRequest()->getMethod() == "POST") {
            $user_data = array(
                'email' => trim($app->getRequest()->request->get('email')),
                'pass' => trim($app->getRequest()->request->get('password')),
                'firstname' => trim($app->getRequest()->request->get('name')),
                'gender' => trim($app->getRequest()->request->get('gender')),
                'date_ofbirth' => trim($app->getRequest()->request->get('birthdate')),
            );

            if(User::userExists($user_data['email'], $app)) {
                $app->setTemplateData(array('title' => 'Signup', 'content_message' => 'Signup was unsuccessful, user with email ' . $user_data['email'] . ' already exists. Try different email'));
                $this->display($app, 'frm_signup.twig');
                return;
            }

            if(User::addUser($user_data, $app)) {
                $app->setTemplateData(array('title' => 'Login', 'content_message' => 'The user is successfully added and can login',));
            }
            else {
                $app->setTemplateData(array('title' => 'Signup', 'content_message' => 'Signup was unsuccessful, try again.',));
                $this->display($app, 'frm_signup.twig');
                return;
            }
        }

        $this->display($app, 'frm_login.twig');
    }

    public function viewSignup($params, $app)
    {
        $app->setTemplateData(array('title' => 'Signup',));

        $this->display($app, 'frm_signup.twig');
    }
}
