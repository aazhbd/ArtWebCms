<?php

use ArtLibs\Controller;
use ArtLibs\User;


class Views extends Controller
{
    public function viewHome($params, $app)
    {
        $app->setTemplateData(array(
                'title' => 'Home',
                'user_var.project_name' => "Test information"
            ));

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

        $user_info = $app->getSession()->get('user_info');

        if($user_info['utype'] == 1) {
            $articles = $this->getArticles($app);
            if($articles) {
                $app->setTemplateData(array('articles' => $articles));
            }
        }

        $this->display($app, 'uhome.twig');
    }

    public function getArticles($app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->select(null)
                ->select(array('id', 'uid', 'category_id', 'url', 'title', 'subtitle', 'body', 'date_inserted'))
                ->orderBy('date_inserted DESC')
                ->fetchAll();
        }
        catch(\Exception $ex){
            $app->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return null;
        }

        if($query == false) {
            return null;
        }
        else {
            return $query;
        }
    }

    public function getArticlesById($aid, $app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->select(null)
                ->select(array('id', 'uid', 'category_id', 'url', 'title', 'subtitle', 'body', 'date_inserted'))
                ->where(array("id" => $aid,))
                ->fetchAll();
        }
        catch(\Exception $ex){
            $app->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return null;
        }

        if($query == false) {
            return null;
        }
        else {
            return $query;
        }
    }

    public function getArticlesByUrl($aurl, $app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->select(null)
                ->select(array('id', 'uid', 'category_id', 'url', 'title', 'subtitle', 'body', 'date_inserted'))
                ->where(array("url" => $aurl,))
                ->fetchAll();
        }
        catch(\Exception $ex){
            $app->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return null;
        }

        if($query == false) {
            return null;
        }
        else {
            return $query;
        }
    }

    public function viewArticle($params, $app) {
        $app->setTemplateData(array('title' => 'Not found'));
        $article = false;

        if(isset($params['article_id'])){
            $aid = $params['article_id'];
            $article = $this->getArticlesById($aid, $app);
        }
        elseif(isset($params['aurl'])) {
            $aurl = $params['aurl'];
            $article = $this->getArticlesByUrl($aurl, $app);
        }

        $user_info = $app->getSession()->get('user_info');

        if($user_info['utype'] == 1) {
            if($article) {
                $app->setTemplateData(array(
                    'title' => $article[0]['title'],
                    'subtitle' => $article[0]['subtitle'],
                    'body' => $article[0]['body'],
                    'article' => $article
                ));
            }
        }

        $this->display($app, 'article.twig');
    }

    public function viewArticleList($params, $app)
    {
        $app->setTemplateData(array(
            'title' => 'All articles',
        ));

        $user_info = $app->getSession()->get('user_info');

        if($user_info['utype'] == 1) {
            $articles = $this->getArticles($app);
            if($articles) {
                $app->setTemplateData(array('articles' => $articles));
            }
        }

        $this->display($app, 'article.twig');
    }

    public function frmArticle($params, $app)
    {
        $app->setTemplateData(array(
                'title' => 'Add new article',
            ));

        $user_info = $app->getSession()->get('user_info');

        if($user_info['utype'] == 1) {
            $categories = $this->getCategories($app);

            if($categories) {
                $app->setTemplateData(array('categories' => $categories));
            }
        }

        $this->display($app, 'frm_article.twig');
    }

    public function getCategories($app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("categories")
                ->fetchAll();
        }
        catch(\Exception $ex){
            $app->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return null;
        }

        if($query == false) {
            return null;
        }
        else {
            return $query;
        }
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
                'ustatus' => 1,
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

    public function viewLogout($param, $app) {
        $app->setTemplateData(array( 'title' => 'Logout', ));

        if($app->getSession()->get('is_authenticated')) {
            $logout = User::clearSession($app);
        }

        if($logout) {
            $app->setTemplateData(array('content_message' => 'The user successfully logged out.'));
        }

        $this->display($app, 'frm_login.twig');
    }
}
