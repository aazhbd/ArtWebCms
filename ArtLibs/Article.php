<?php

namespace ArtLibs;


class Article
{

    /**
     * @param Application $app
     * @param null $state
     * @param null $category_id
     * @return array|bool
     */
    public static function getArticles(Application $app, $state = null, $category_id = null)
    {
        $cond = array();

        if ($category_id !== null) {
            $cond['category_id'] = (int)$category_id;
        }

        if ($state !== null) {
            $cond['state'] = $state;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->from("articles");
            $query->where($cond);
            $q = $query->orderBy('date_inserted ASC')->fetchAll();
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $q;
    }

    /**
     * @param $aid
     * @param Application $app
     * @return bool|mixed
     */
    public static function getArticleById($aid, Application $app)
    {
        if (!isset($aid)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->where(array("id" => $aid))
                ->fetch();
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param $article_data
     * @param $aid
     * @param Application $app
     * @return bool|int|\PDOStatement
     */
    public static function updateArticle($article_data, $aid, Application $app)
    {
        if (!isset($article_data) || !isset($aid)) {
            return false;
        }

        $article_data['date_updated'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->update('articles', $article_data, $aid);
            $executed = $query->execute(true);
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $aurl
     * @param Application $app
     * @return bool|mixed
     */
    public static function getArticleByUrl($aurl, Application $app)
    {
        if (!isset($aurl)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->where(array("url" => $aurl, "state" => 0))
                ->fetch();
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param $article_data
     * @param Application $app
     * @return bool|int
     */
    public static function addArticle($article_data, Application $app)
    {
        if (empty($article_data)) {
            return false;
        }

        $article_data['date_inserted'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('articles')->values($article_data);
            $executed = $query->execute(true);
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }
}

/**
 * An open source web application development framework for PHP 5.
 * @author        ArticulateLogic Labs
 * @author        Abdullah Al Zakir Hossain, Email: aazhbd@yahoo.com
 * @copyright     Copyright (c)2009-2014 ArticulateLogic Labs
 * @license       MIT License
 */
