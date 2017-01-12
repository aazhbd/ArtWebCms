# ArtWebCMS

ArtWebCMS is a fast, secure, feature rich, scalable Content Management System, powered by ArtWeb using Symfony2 components integrating with the best practices of MVC.

![ArtWebCMS](http://articulatelogic.com/file/view/artwebcms/)


### Requirements
----------------

Requires `PHP 5.3` or greater, `composer 1.2` or greater

### Features
------------

 - The initial setup is very small but flexible.
 - The project can be extended by adding any number of components, installed and maintained with ```composer```.
 - The code and structure adheres to the proven best practices of OOP and MVC, creating highly maintainable code.
 - The operation flow and points of execution makes debugging easier.
 - The Route manager is flexible and mimics functionalities of proven flexibility of development such as Django.
 - It contains a flexible and expendable Configuration manager that handles system values along with custom user values.

### Components
--------------

an initial installation contains,

 - Symfony 3 components
 - FluentPDO is used for database abstraction
 - Twig is used as Template manager
 - JQuery and JQuery UI is used for front end controls including validation
 - Editor formatting with markdown


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

> **Abdullah Al Zakir Hossain**

- Email:   <aazhbd@yahoo.com>
- Github:   <http://github.com/aazhbd>
- Profile:   <https://de.linkedin.com/in/aazhbd>
