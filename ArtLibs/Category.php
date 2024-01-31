<?php

namespace ArtLibs;

use \Envms\FluentPDO\Exception as DBException;


class Category
{
    /**
     * @param Application $app
     * @param $state
     * @return array|bool|null
     * @throws DBException
     */
    public static function getCategories(Application $app, $state = null)
    {
        if (!isset($state)) {
            $query = $app->getDataManager()->getDataManager()->from("categories");
        } else {
            $query = $app->getDataManager()->getDataManager()->from("categories")->where(array("state" => $state));
        }

        try {
            $q = $query->fetchAll();
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return null;
        }

        return $q;
    }

    /**
     * @param Application $app
     * @param $cat_id
     * @return false|mixed|null
     */
    public static function getCategoryById(Application $app, $cat_id)
    {
        try {
            $query = $app->getDataManager()->getDataManager()->from("categories")
                ->where(array("id" => $cat_id,))
                ->fetch();
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return null;
        }

        return $query;
    }

    /**
     * @param $cat_name
     * @param Application $app
     * @return null
     */
    public static function getCategoryByName($cat_name, Application $app)
    {
        try {
            $query = $app->getDataManager()->getDataManager()->from("categories")
                ->where(array("catname" => $cat_name))
                ->fetch();
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return null;
        }

        return $query;
    }

    /**
     * @param array $category
     * @param Application $app
     * @return bool
     */
    public static function addCategory($category = array(), Application $app)
    {
        if (empty($category)) {
            return false;
        }

        $category['date_inserted'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('categories')->values($category);
            $executed = $query->execute(true);
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $cat_id
     * @param array $category
     * @param Application $app
     * @return bool
     */
    public static function updateCategory($cat_id, $category = array(), Application $app)
    {
        if (empty($category) || !isset($cat_id)) {
            return false;
        }

        $category['date_updated'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->update('categories', $category, $cat_id);
            $executed = $query->execute(true);
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $state
     * @param $category_id
     * @param Application $app
     * @return bool
     */
    public static function setState($state, $category_id, Application $app)
    {
        if (!isset($state) || !isset($category_id)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()
                ->update('categories', array('state' => $state, 'date_updated' => new \FluentLiteral('NOW()')), $category_id);
            $executed = $query->execute(true);
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }
}

/**
 * An open source web application development framework for PHP.
 * @author        ArticulatedLogic
 * @author        Abdullah Al Zakir Hossain, Email: aazhbd@yahoo.com
 * @copyright     Copyright (c)2009-2014 ArticulatedLogic
 * @license       MIT License
 */
