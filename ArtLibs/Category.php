<?php

namespace ArtLibs;


class Category
{
    public static function getCategories($app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("categories")
                ->fetchAll();
        }
        catch(\PDOException $ex){
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