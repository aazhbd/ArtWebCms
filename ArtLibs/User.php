<?php

namespace ArtLibs;


class User
{
    protected $user_status;

    protected $authenticated;

    protected $user_info;

    protected $data;

    protected $error;

    public function __construct($app, $email, $pass) {
        $this->data = $app->getDataManager()->getDataManager();
        $this->error = $app->getErrorManager();

        $this->user_info = array();

        $this->authenticated = $this->getUser($email, $pass);
    }

    public function getUser($user, $pass) {
        try {
            $query = $this->getData()->from("users")
                ->select(null)
                ->select(array('id', 'firstname', 'lastname', 'email', 'pass', 'ustatus', 'utype', 'validator', 'state'))
                ->where(array("email" => $user, "pass" => $pass))
                ->fetch();
        }
        catch(\Exception $ex){
            $this->getError()->addMessage("Error retrieving user information : " . $ex->getMessage());
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

    public function addUser($uinfo=array()) {
        if (empty($uinfo)) {
            return false;
        }

        try {
            $query = $this->getData()->insertInto('users')->values($uinfo)->execute();
        }
        catch(\Exception $ex){
            $this->getError()->addMessage("Error adding new user: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function disableUser($user_id=null) {
        if($user_id == null) {
            return false;
        }

        try {
            $query = $this->getData()->update('users', array('state' => 1), $user_id)->execute();
        }
        catch(\Exception $ex){
            $this->getError()->addMessage("Error disabling user: " . $ex->getMessage());
            return false;
        }

        return true;
    }

    public function enableUser($user_id=null) {
        if($user_id == null) {
            return false;
        }

        try {
            $query = $this->getData()->update('users', array('state' => 0), $user_id)->execute();
        }
        catch(\Exception $ex){
            $this->getError()->addMessage("Error disabling user: " . $ex->getMessage());
            return false;
        }

        return true;
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

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return User
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     * @return User
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

}