<?php

namespace ArtLibs;


class Article
{
    /**
     * @param Application $app
     * @param null $state
     * @return bool
     */
    public static function getArticles(Application $app, $state = null)
    {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles");
            if ($state != null) {
                $query->where(array('state' => $state));
            }
            $q = $query->orderBy('date_inserted DESC')->fetchAll();
        } catch (\PDOException $ex) {
            $app->getErrorManager()->addMessage("Error : " . $ex->getMessage());
            return false;
        }

        return $q;
    }

    /**
     * @param $aid
     * @param Application $app
     * @return bool
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
     * @return bool
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
     * @return bool
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
     * @return bool
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
