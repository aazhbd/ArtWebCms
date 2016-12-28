# ArtWebCMS

ArtWebCMS is a fast, secure, feature rich, scalable Content Management System, powered by ArtWeb using Symfony2 components integrating with the best practices of MVC.

![ArtWebCMS](http://articulatelogic.com/file/view/artwebcms/)


### Requirements
----------------

Requires `PHP 5.3` or greater, `composer 1.2` or greater

### Install instructions
------------------------

Since ArtWebCMS is based on [ArtWeb](http://articulatelogic.com/a/artweb/), the same [steps](http://articulatelogic.com/a/artweb/) can be followed to deploy it.

After the code is deployed, it is necessary to import the included artcmsdb.sql file to MySQL. This creates an initial user for admin and some dummy pages.

The interface can be accessed by opening, <http://localhost:8080/ArtWebCms/webroot/> the webserver URL and folder address can be different based on deployment.

After deploying the copy with database and webserver, the admin interface can be accessed with username: `admin` and password: `admin` and the top menu can be edited by editing the file in `/App/views/home.twig`

### License
-----------

The code is released under MIT License.


### Contact
-----------

[Abdullah Al Zakir Hossain](https://de.linkedin.com/in/aazhbd)
<aazhbd@yahoo.com>
