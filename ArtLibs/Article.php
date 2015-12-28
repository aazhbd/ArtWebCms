<?php

namespace ArtLibs;


class Article
{
    /**
     * @return mixed
     */
    public static function getArticles($app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->select(null)
                ->select(array('id', 'uid', 'category_id', 'url', 'title', 'subtitle', 'body', 'date_inserted'))
                ->orderBy('date_inserted DESC')
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

    /**
     * @return mixed
     */
    public static function getArticlesById($aid, $app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->select(null)
                ->select(array('id', 'uid', 'category_id', 'url', 'title', 'subtitle', 'body', 'date_inserted'))
                ->where(array("id" => $aid,))
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

    /**
     * @return mixed
     */
    public static function getArticlesByUrl($aurl, $app) {
        try {
            $query = $app->getDataManager()->getDataManager()->from("articles")
                ->select(null)
                ->select(array('id', 'uid', 'category_id', 'url', 'title', 'subtitle', 'body', 'date_inserted'))
                ->where(array("url" => $aurl,))
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

    /**
     * @return boolean
     */
    public static function addArticle($article_data, $app) {
        if (empty($article_data)) {
            return false;
        }

        $article_data['date_inserted'] = new \FluentLiteral('NOW()');

        try {
            $query = $app->getDataManager()->getDataManager()->insertInto('articles')->values($article_data);
            $executed = $query->execute(true);
        }
        catch(\PDOException $ex){
            $app->getErrorManager()->addMessage("Error adding new article: " . $ex->getMessage());
            return false;
        }

        return $executed;
    }
}