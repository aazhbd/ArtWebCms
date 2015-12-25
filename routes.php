<?php

$routes = array(
    'urls' => array(
        '' => '/controller/Views/viewCustom',
        '/home' => '/controller/Views/viewHome',
        '/login' => '/controller/Views/viewLogin',
        '/logout' => '/controller/Views/viewLogout',
        '/signup' => '/controller/Views/viewSignup',
        '/a/(?<aurl>[A-Za-z_][A-Za-z0-9_]*)' => '/controller/Views/viewArticle',
        '/a/(?<aid>\d+)' => '/controller/Views/viewArticle',
    )
);

return $routes['urls'];
