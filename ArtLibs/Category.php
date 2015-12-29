<?php

namespace ArtLibs;


class Category
{
    /**
     * @param $app
     * @param null $state
     * @return mixed
     */
    public static function getCategories($app, $state=null) {
        if(!isset($state)) {
            $query = $app->getDataManager()->getDataManager()->from("categories");
        }
        else {
            $query = $app->getDataManager()->getDataManager()->from("categories")->where(array("state" => $state));
        }

        try {
            $q = $query->fetchAll();
        }
        catch(\PDOException $ex){
            $app->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return null;
        }

        return $q;
    }

    /**
     * @param $cat_id
     * @param $app
     * @return mixed
     */
    public static function getCategoryById($cat_id, $app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("categories")
                ->where(array("id" => $cat_id,))
                ->fetch();
        }
        catch(\PDOException $ex){
            $app->getErrorManager()->addMessage("Error retrieving user information : " . $ex->getMessage());
            return null;
        }

        return $query;
    }

    /**
     * @param array $category
     * @param $app
     * @return bool
     */
    public static function addCategory($category=array(), $app) {
        if (empty($category)) {
            return false;
        }

        $category['date_inserted'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('categories')->values($category);
            $executed = $query->execute(true);
        }
        catch(\PDOException $ex){
            $app->getErrorManager()->addMessage("Error adding new user: " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $cat_id
     * @param array $category
     * @param $app
     * @return bool
     */
    public static function updateCategory($cat_id, $category=array(), $app) {
        if (empty($category) || !isset($cat_id)) {
            return false;
        }

        $category['date_updated'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->update('categories', $category, $cat_id);
            $executed = $query->execute(true);
        }
        catch(\PDOException $ex){
            $app->getErrorManager()->addMessage("Error adding new user: " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $state
     * @param $category_id
     * @param $app
     * @return bool
     */
    public static function setStateCategory($state, $category_id, $app) {
        if(!isset($state) || !isset($category_id)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->update('categories', array('state' => $state), $category_id);
            $executed = $query->execute(true);
        }
        catch(\PDOException $ex){
            $app->getErrorManager()->addMessage("Error disabling user: " . $ex->getMessage());
            return false;
        }

        return $executed;
    }
}