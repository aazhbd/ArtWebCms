<?php

namespace ArtLibs;

use Envms\FluentPDO\Literal;
use Envms\FluentPDO\Exception as DBException;
use PDOStatement;

class Article
{

    /**
     * @param Application $app
     * @param $state
     * @param $category_id
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
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $q;
    }

    /**
     * @param Application $app
     * @param $aid
     * @return false|mixed
     */
    public static function getArticleById(Application $app, $aid)
    {
        if (!isset($aid)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->where(array("id" => $aid))
                ->fetch();
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param Application $app
     * @param $article_data
     * @param $aid
     * @return bool|int|PDOStatement
     */
    public static function updateArticle(Application $app, $article_data, $aid)
    {
        if (!isset($article_data) || !isset($aid)) {
            return false;
        }

        $article_data['date_updated'] = new Literal('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->update('articles', $article_data, $aid);
            $executed = $query->execute(true);
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $executed;
    }

    /**
     * @param $article_url
     * @param Application $app
     * @return bool|mixed
     */
    public static function getArticleByUrl(Application $app, $article_url)
    {
        if (!isset($article_url)) {
            return false;
        }

        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->where(array("url" => $article_url, "state" => 0))
                ->fetch();
        } catch (DBException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $query;
    }

    /**
     * @param Application $app
     * @param $article_data
     * @return bool|int
     */
    public static function addArticle(Application $app, $article_data)
    {
        if (empty($article_data)) {
            return false;
        }

        $article_data['date_inserted'] = new Literal('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('articles')->values($article_data);
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
 * @copyright     Copyright (c)2009-2024 ArticulatedLogic
 * @license       MIT License
 */
