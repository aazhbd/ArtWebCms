<?php

use ArtLibs\Controller;
use ArtLibs\User;
use ArtLibs\Article;
use ArtLibs\Category;


class Views extends Controller
{
    /**
     * @param $params
     * @param $app
     */
    public function viewRoot($params, $app)
    {
        $app->setTemplateData(
            array(
                'title' => 'Start',
                'body_content' => 'The start page loaded from controller/view.php.'
            )
        );

        $this->display($app, 'home.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewHome($params, $app)
    {
        $app->setTemplateData(array(
            'title' => 'Home',
        ));

        if ($app->getRequest()->getMethod() == "POST") {
            $email = $app->getRequest()->request->get('email');
            $pass = $app->getRequest()->request->get('password');

            $user = new User($app, $email, $pass);

            if (!$user->isAuthenticated()) {
                $app->setTemplateData(array('content_message' => 'Login unsuccessful, try again.'));
                $this->display($app, 'frm_login.twig');
                return;
            } else {
                $app->setTemplateData(array('content_message' => 'Login successful.'));
            }
        }

        $user_info = $app->getSession()->get('user_info');

        if ($user_info['utype'] == 1) {
            $articles = Article::getArticles($app, 0);
            if ($articles) {
                $app->setTemplateData(array('subtitle' => 'Active Articles', 'articles' => $articles));
            }
        }

        $this->display($app, 'uhome.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewArticle($params, $app)
    {
        $app->setTemplateData(array('title' => 'Not found'));
        $article = false;

        if (isset($params['aid'])) {
            $aid = $params['aid'];
            $article = Article::getArticleById($aid, $app);
        } elseif (isset($params['aurl'])) {
            $aurl = $params['aurl'];
            $article = Article::getArticleByUrl($aurl, $app);
        }

        if ($article) {
            $app->setTemplateData(array(
                'title' => $article['title'],
                'subtitle' => $article['subtitle'],
                'body' => stripslashes($article['body']),
                'article' => $article
            ));
        }

        $this->display($app, 'list_article.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewArticleList($params, $app)
    {
        $app->setTemplateData(array(
            'title' => 'Articles List',
        ));

        $user_info = $app->getSession()->get('user_info');

        if ($user_info['utype'] == 1) {
            if ($app->getRequest()->getMethod() == "POST") {
                $article_data = array(
                    'uid' => $user_info['id'],
                    'title' => trim($app->getRequest()->request->get('title')),
                    'subtitle' => trim($app->getRequest()->request->get('subtitle')),
                    'url' => strtolower(trim($app->getRequest()->request->get('aurl'))),
                    'category_id' => trim($app->getRequest()->request->get('category')),
                    'body' => addslashes(trim($app->getRequest()->request->get('abody'))),
                    'state' => addslashes(trim($app->getRequest()->request->get('state'))),
                );

                $invalid_chars = array(" ", "\n", "/", "\\", "$", "#", "@", "^", "&", "*");
                $article_data['url'] = str_replace($invalid_chars, "_", $article_data['url']);

                if($app->getRequest()->request->get('editval')) {
                    $aid = trim($app->getRequest()->request->get('editval'));
                    $app->setTemplateData(array('content_message' => (Article::updateArticle($article_data, $aid, $app)) ? "Article updated successfully" : "Article update failed"));
                }
                elseif (Article::addArticle($article_data, $app)) {
                    $app->setTemplateData(array('content_message' => "New article added successfully."));
                }
                else {
                    $app->setTemplateData(array('content_message' => "Article couldn't be saved."));
                }
            }

            $articles = Article::getArticles($app);
            if ($articles) {
                $app->setTemplateData(array('articles' => $articles));
            }
        }
        else {
            $app->setTemplateData(array('content_message' => 'Not found or accessible'));
        }

        $this->display($app, 'list_article.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function frmArticle($params, $app)
    {
        $app->setTemplateData(array(
            'title' => 'Add new article',
        ));

        $user_info = $app->getSession()->get('user_info');
        if ($user_info['utype'] == 1) {
            if(isset($params['opt']) && isset($params['aid'])) {
                $action = $params['opt'];
                $aid = $params['aid'];
                $app->setTemplateData(array('article' => Article::getArticleById($aid, $app), 'action' => "edit"));
            }

            $categories = Category::getCategories($app, 0);

            if ($categories) {
                $app->setTemplateData(array('categories' => $categories));
            }
        }

        $this->display($app, 'frm_article.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewCategoryList($params, $app)
    {
        $app->setTemplateData(array(
            'title' => 'Category List',
        ));

        $user_info = $app->getSession()->get('user_info');

        if ($user_info['utype'] == 1) {
            if (isset($params[2])) {
                $action = $params[1];
                $cat_id = $params[2];

                if ($action == "edit") {
                    $cat_pre = Category::getCategoryById($cat_id, $app);
                    $app->setTemplateData(array('action' => 'edit', 'cat_id' => $cat_id, 'cat_pre' => $cat_pre));
                }
                elseif ($action == "enable") {
                    $app->setTemplateData(
                        array(
                            'content_message' => (Category::setState(0, $cat_id, $app)) ? 'Category is ' . $params[1] . 'd.' : 'State change failed'
                        )
                    );
                }
                elseif ($action == "disable") {
                    $app->setTemplateData(
                        array(
                            'content_message' => (Category::setState(1, $cat_id, $app)) ? 'Category is ' . $params[1] . 'd.' : 'State change failed'
                        )
                    );
                }
            }

            if ($app->getRequest()->getMethod() == "POST") {
                $category = array(
                    'catname' => trim($app->getRequest()->request->get('catname')),
                );

                if ($app->getRequest()->request->get('editval')) {
                    $cid = $app->getRequest()->request->get('editval');
                    $app->setTemplateData(
                        array(
                            'content_message' => (Category::updateCategory($cid, $category, $app)) ? 'Category successfully updated' : 'Category save failed'
                        )
                    );
                }
                elseif (Category::addCategory($category, $app)) {
                    $app->setTemplateData(array('content_message' => 'New category successfully added'));
                }
                else {
                    $app->setTemplateData(array('content_message' => 'New category save failed'));
                }
            }

            $categories = Category::getCategories($app);
            if ($categories) {
                $app->setTemplateData(array('categories' => $categories));
            }
        }
        else {
            $app->setTemplateData(array('content_message' => 'Not found or accessible'));
        }

        $this->display($app, 'list_category.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewUserList($params, $app)
    {
        $app->setTemplateData(array(
            'title' => 'Users List',
        ));

        $user_info = $app->getSession()->get('user_info');

        if ($user_info['utype'] == 1) {
            if(isset($params['opt']) && isset($params['uid'])) {
                if($params['opt'] == "enable") {
                    $msg = (User::setState(0, $params['uid'], $app)) ? "User Enabled" : "User state change failed";
                    $app->setTemplateData(array('content_message' => $msg));
                }
                elseif($params['opt'] == "disable") {
                    $msg = (User::setState(1, $params['uid'], $app)) ? "User Disabled" : "User state change failed";
                    $app->setTemplateData(array('content_message' => $msg));
                }
                elseif($params['opt'] == "edit") {
                    $update_user = User::getUserById($params['uid'], $app);
                    $app->setTemplateData(array('action' => 'edit', 'update_user' => $update_user));
                }
                else {
                    $app->setTemplateData(array('content_message' => 'Not found or accessible'));
                }
            }

            if ($app->getRequest()->getMethod() == "POST") {
                $user_data = array(
                    'email' => trim($app->getRequest()->request->get('email')),
                    'pass' => trim($app->getRequest()->request->get('password')),
                    'firstname' => trim($app->getRequest()->request->get('name')),
                    'gender' => trim($app->getRequest()->request->get('gender')),
                    'date_ofbirth' => trim($app->getRequest()->request->get('birthdate')),
                    'ustatus' => 1,
                );

                if($app->getRequest()->request->get('editval')) {
                    $uid = $app->getRequest()->request->get('editval');
                    $app->setTemplateData(array('content_message' => (User::updateUser($uid, $user_data, $app) ? "User updated successfully" : "User couldn't be updated")));
                }
                elseif (User::userExists($user_data['email'], $app)) {
                    $app->setTemplateData(array('content_message' => 'User with email ' . $user_data['email'] . ' already exists. Try different email'));
                }
                elseif (User::addUser($user_data, $app)) {
                    $app->setTemplateData(array('content_message' => "New user added."));
                }
                else {
                    $app->setTemplateData(array('content_message' => "User couldn't be saved."));
                }
            }

            $users = User::getUsers($app);
            if ($users) {
                $app->setTemplateData(array('users' => $users));
            }
        }
        else {
            $app->setTemplateData(array('content_message' => 'Not found or accessible'));
        }

        $this->display($app, 'list_users.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewLogin($params, $app)
    {
        $app->setTemplateData(array('title' => 'Login',));

        if ($app->getRequest()->getMethod() == "POST") {
            $user_data = array(
                'email' => trim($app->getRequest()->request->get('email')),
                'pass' => trim($app->getRequest()->request->get('password')),
                'firstname' => trim($app->getRequest()->request->get('name')),
                'gender' => trim($app->getRequest()->request->get('gender')),
                'date_ofbirth' => trim($app->getRequest()->request->get('birthdate')),
                'ustatus' => 1,
            );

            if (User::userExists($user_data['email'], $app)) {
                $app->setTemplateData(array('title' => 'Signup', 'content_message' => 'Signup was unsuccessful, user with email ' . $user_data['email'] . ' already exists. Try different email'));
                $this->display($app, 'frm_signup.twig');
                return;
            }

            if (User::addUser($user_data, $app)) {
                $app->setTemplateData(array('title' => 'Login', 'content_message' => 'The user is successfully added and can login',));
            } else {
                $app->setTemplateData(array('title' => 'Signup', 'content_message' => 'Signup was unsuccessful, try again.',));
                $this->display($app, 'frm_signup.twig');
                return;
            }
        }

        $this->display($app, 'frm_login.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewSignup($params, $app)
    {
        $app->setTemplateData(array('title' => 'Signup',));

        $this->display($app, 'frm_signup.twig');
    }

    /**
     * @param $params
     * @param $app
     */
    public function viewLogout($params, $app)
    {
        $app->setTemplateData(array('title' => 'Logout'));

        $logout = false;

        if ($app->getSession()->get('is_authenticated')) {
            $logout = User::clearSession($app);
        }

        if ($logout) {
            $app->setTemplateData(array('content_message' => 'The user successfully logged out.'));
        }

        $this->display($app, 'frm_login.twig');
    }
}
