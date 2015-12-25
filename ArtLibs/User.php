<?php

namespace ArtLibs;


class User
{
    protected $user_status;

    protected $authenticated;

    protected $user_info;

    protected $app;

    public function __construct($app, $email, $pass) {
        $this->app = $app;

        $this->user_info = array();

        $this->authenticated = $this->getUser($email, $pass);
        if($this->authenticated) {
            $this->setSession();
        }
    }

    /**
     * @return boolean
     */
    public function getUser($user, $pass) {
        try {
            $query = $this->app->getDataManager()->getDataManager()->from("users")
                ->select(null)
                ->select(array('id', 'firstname', 'lastname', 'email', 'pass', 'ustatus', 'utype', 'state'))
                ->where(array("email" => $user, "pass" => $pass))
                ->fetch();
        }
        catch(\Exception $ex){
            $this->getApp()->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return false;
        }

        if($query == false) {
            return false;
        }
        else {
            $this->setUserInfo($query);
            return true;
        }
    }

    public function setSession() {
        if(!$this->getApp()->getSession()->isStarted()) {
            try {
                $this->getApp()->getRequest()->getSession()->start();
            }
            catch(\Exception $ex) {
                $this->getApp()->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
                return false;
            }
        }

        $this->getApp()->getSession()->set('is_authenticated', true);
        $this->getApp()->getSession()->set('user_info', $this->getUserInfo());

        return true;
    }

    /**
     * @return boolean
     */
    public static function userExists($email, $app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("users")
                ->select(null)
                ->select(array('id'))
                ->where(array("email" => $email))
                ->fetch();
        }
        catch(\Exception $ex){
            $error = new ErrorManager();
            $error->addMessage("Error retrieving user information : " . $ex->getMessage());
            return false;
        }

        if($query == false) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * @return boolean
     */
    public static function addUser($uinfo=array(), $app) {
        if (empty($uinfo)) {
            return false;
        }

        $executed = false;

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('users')->values($uinfo);
            $executed = $query->execute(true);
        }
        catch(\Exception $ex){
            $error = new ErrorManager();
            $error->addMessage("Error adding new user: " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @return boolean
     */
    public static function disableUser($user_id=null, $app) {
        if($user_id == null) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->update('users', array('state' => 1), $user_id);
            $query->execute(true);
        }
        catch(\Exception $ex){
            $error = new ErrorManager();
            $error->addMessage("Error disabling user: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @return boolean
     */
    public static function enableUser($user_id=null, $app) {
        if($user_id == null) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->update('users', array('state' => 0), $user_id)->execute(true);
        }
        catch(\Exception $ex){
            $error = new ErrorManager();
            $error->addMessage("Error disabling user: " . $ex->getMessage());
            return false;
        }

        return true;
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
        if($this->isAuthenticated())
        {
            $info = $this->getUserInfo();
            return $info['ustatus'];
        }
        return 0;
    }

    /**
     * @param mixed $user_status
     * @return User
     */
    public function setUserStatus($user_status)
    {
        if($this->isAuthenticated()) {
            $info = $this->getUserInfo();
            $this->user_status = $info['ustatus'];
        }
        $this->user_status = 0;

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