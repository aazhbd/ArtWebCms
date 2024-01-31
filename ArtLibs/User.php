<?php

namespace ArtLibs;

use \Envms\FluentPDO\Literal;
use PDOStatement;


class User
{
    protected $user_status;

    protected $authenticated;

    protected $user_info;

    protected $app;

    /**
     * @param Application $app
     * @param $email
     * @param $pass
     */
    public function __construct(Application $app, $email, $pass)
    {
        $this->app = $app;

        $this->user_info = array();

        $this->authenticated = $this->getUser($email, $pass);
        if ($this->authenticated) {
            $this->setSession();
        }
    }

    /**
     * @param $user
     * @param $pass
     * @return false|mixed
     */
    public function getUser($user, $pass)
    {
        try {
            $query = $this->app->getDataManager()->getDataManager()->from("users")
                ->where(array("email" => $user, "pass" => $pass, "ustatus" => 1, "state" => 0))
                ->fetch();
        } catch (\Exception $ex) {
            $this->getApp()->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        if ($query) {
            $this->setUserInfo($query);
            $this->updateLoginTime($query['id']);
        }

        return $query;
    }

    /**
     * @return Application
     */
    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * @param Application $app
     * @return $this
     */
    public function setApp(Application $app): User
    {
        $this->app = $app;
        return $this;
    }

    /**
     * @param $uid
     * @return bool
     */
    public function updateLoginTime($uid)
    {
        if (!isset($uid)) {
            return false;
        }

        try {
            $query = $this->getApp()->getDataManager()->getDataManager()
                ->update('users', array('date_lastlogin' => new \FluentLiteral('NOW()')), $uid);
            $executed = $query->execute(true);
        } catch (\Exception $ex) {
            $this->getApp()->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @return boolean
     */
    public function setSession()
    {
        if (!$this->getApp()->getSession()->isStarted()) {
            try {
                $this->getApp()->getRequest()->getSession()->start();
            } catch (\Exception $ex) {
                $this->getApp()->getErrorManager()->addMessage("Error : " . $ex->getMessage());
                return false;
            }
        }

        $this->getApp()->getSession()->set('is_authenticated', true);
        $this->getApp()->getSession()->set('user_info', $this->getUserInfo());

        return true;
    }

    /**
     * @return array
     */
    public function getUserInfo()
    {
        return $this->user_info;
    }

    /**
     * @param array $user_info
     * @return User
     */
    public function setUserInfo($user_info)
    {
        $this->user_info = $user_info;
        return $this;
    }

    /**
     * @param Application $app
     * @return bool
     */
    public static function clearSession(Application $app): bool
    {
        if (!$app->getSession()->get('is_authenticated')) {
            return false;
        }
        $app->getSession()->set('is_authenticated', false);
        $app->getSession()->set('user_info', null);
        return true;
    }

    /**
     * @param $email
     * @param Application $app
     * @return bool
     */
    public static function userExists($email, Application $app): bool
    {
        try {
            $query = $app->getDataManager()->getDataManager()->from("users")
                ->select(null)
                ->select(array('id'))
                ->where(array("email" => $email))
                ->fetch();
        } catch (\Exception $ex) {
            $error = new ErrorManager();
            $error->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param Application $app
     * @param array $user_info
     * @return bool
     */
    public static function addUser(Application $app, array $user_info = array()): bool
    {
        if (empty($user_info)) {
            return false;
        }

        $user_info['date_inserted'] = new Literal('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('users')->values($user_info);
            $executed = $query->execute(true);
        } catch (\Exception $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param Application $app
     * @param $uid
     * @param array $user_info
     * @return bool|int|PDOStatement
     */
    public static function updateUser(Application $app, $uid, array $user_info = array())
    {
        if (empty($user_info) || !isset($uid)) {
            return false;
        }

        $user_info['date_updated'] = new Literal('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->update('users', $user_info, $uid);
            $executed = $query->execute(true);
        } catch (\Exception $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param Application $app
     * @return array|bool
     */
    public static function getUsers(Application $app)
    {
        try {
            $query = $app->getDataManager()->getDataManager()->from("users")->fetchAll();
        } catch (\Exception $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param Application $app
     * @param $uid
     * @return false|mixed
     */
    public static function getUserById(Application $app, $uid)
    {
        try {
            $query = $app->getDataManager()->getDataManager()->from("users")
                ->select(null)
                ->select(array('id', 'firstname', 'lastname', 'email', 'pass', 'gender', 'ustatus', 'utype', 'state'))
                ->where(array("id" => $uid))
                ->fetch();
        } catch (\Exception $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param $state
     * @param $uid
     * @param Application $app
     * @return bool
     */
    public static function setState($state, $uid, Application $app)
    {
        if (!isset($state) || !isset($uid)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()
                ->update('users', array('date_updated' => new \FluentLiteral('NOW()'), 'state' => $state), $uid);
            $executed = $query->execute(true);
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @return mixed
     */
    public function getUserStatus()
    {
        if ($this->isAuthenticated()) {
            $info = $this->getUserInfo();
            return $info['ustatus'];
        }
        return 0;
    }

    /**
     * @return User
     * @internal param mixed $user_status
     */
    public function setUserStatus()
    {
        if ($this->isAuthenticated()) {
            $info = $this->getUserInfo();
            $this->user_status = $info['ustatus'];
        } else {
            $this->user_status = 0;
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * @param boolean $authenticated
     * @return User
     */
    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;
        return $this;
    }

}
