<?php

$routes = array(
    'urls' => array(
        '' => '/controller/Views/viewRoot',
        '/home' => '/controller/Views/viewHome',
        '/login' => '/controller/Views/viewLogin',
        '/logout' => '/controller/Views/viewLogout',
        '/signup' => '/controller/Views/viewSignup',

        '/a/(?<aurl>[A-Za-z_][A-Za-z0-9_]*)' => '/controller/Views/viewArticle',
        '/a/(?<aid>\d+)' => '/controller/Views/viewArticle',
        '/article/add' => '/controller/Views/frmArticle',
        '/article/list' => '/controller/Views/viewArticleList',
        '/article/(?<opt>[A-Za-z_][A-Za-z0-9_]*)/(?<aid>\d+)' => '/controller/Views/frmArticle',

        '/user/list' => '/controller/Views/viewUserList',
        '/user/(?<opt>[A-Za-z_][A-Za-z0-9_]*)/(?<uid>\d+)' => '/controller/Views/viewUserList',

        '/category/list' => '/controller/Views/viewCategoryList',
        '/category/(?<opt>[A-Za-z_][A-Za-z0-9_]*)/(?<cid>\d+)' => '/controller/Views/viewCategoryList',
    )
);

return $routes['urls'];
