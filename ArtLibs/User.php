<?php

namespace ArtLibs;


class User
{
    protected $user_status;

    protected $authenticated;

    protected $user_info;

    protected $app;

    /**
     * User constructor.
     * @param $app
     * @param $email
     * @param $pass
     */
    public function __construct($app, $email, $pass)
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
     * @return bool
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
     * @param $app
     * @return bool
     */
    public static function clearSession($app)
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
     * @param $app
     * @return bool
     */
    public static function userExists($email, $app)
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
     * @param array $uinfo
     * @param $app
     * @return bool
     */
    public static function addUser($uinfo = array(), $app)
    {
        if (empty($uinfo)) {
            return false;
        }

        $uinfo['date_inserted'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('users')->values($uinfo);
            $executed = $query->execute(true);
        } catch (\Exception $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $uid
     * @param array $uinfo
     * @param $app
     * @return bool
     */
    public static function updateUser($uid, $uinfo = array(), $app)
    {
        if (empty($uinfo) || !isset($uid)) {
            return false;
        }

        $uinfo['date_updated'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->update('users', $uinfo, $uid);
            $executed = $query->execute(true);
        } catch (\Exception $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $app
     * @return mixed
     */
    public static function getUsers($app)
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
     * @param $uid
     * @param $app
     * @return bool
     */
    public static function getUserById($uid, $app)
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
     * @param $app
     * @return bool
     */
    public static function setState($state, $uid, $app)
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
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param mixed $app
     * @return User
     */
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
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

}