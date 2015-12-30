<?php

$conf = array(
    'production' => array(
        'db_name' => 'prod_db',
        'development_mode' => false
    ),
    'development' => array(
        'db_name' => 'artcmsdb',
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => '',
        'development_mode' => true,
        'path_sys_template' => '/Template/base.twig',
        'path_static' => '/Template/static/',
        'path_user_template' => '/App/views',
        'user_var' => array(
            'project_name' => 'ArtWebCms :: Articulate Web Content Management System',
            'project_static' => '/App/static',
        ),
    ),
    'staging' => array(
        'db_name' => 'dev_db',
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => '1234',
        'development_mode' => true
    )
);

return $conf['development'];

