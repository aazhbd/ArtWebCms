# ArtWebCMS

ArtWebCMS is a fast, secure, feature rich, scalable Content Management System, powered by ArtWeb using Symfony2 components integrating with the best practices of MVC.

![ArtWebCMS](http://articulatelogic.com/file/view/artwebcms/)

### Features
------------

 - A comprehensive Admin panel that enables,
     - Page creation, editing and deletion with selected category, using markdown.
     - Category creation, edition and deletion.
     - User management with password update and add, edit more users.
     - Allows creating basic websites and blogs with high number of pages with high control.
 - User login and signup process included
 - The initial setup is very small but flexible.
 - The project can be extended by adding any number of components, installed and maintained with ```composer```.
 - More static libraries and components can be added in project specific static container.
 - The code and structure adheres to the proven best practices of OOP and MVC, creating highly maintainable code.
 - The operation flow and points of execution makes debugging easier.
 - The Route manager is flexible and mimics functionalities of proven flexibility of development such as Django.
 - It contains a flexible and expendable Configuration manager that handles system values along with custom user values.
 - The datamanager is adaptable to any other ORM or SQL builder with any DBMS, including MySQL, PostgreSQL, SQLite etc.

### Requirements
----------------

Requires `PHP 5.3` or greater, `composer 1.2` or greater

### Install instructions
------------------------

Since ArtWebCMS is based on [ArtWeb](http://articulatelogic.com/a/artweb/), the same [steps](http://articulatelogic.com/a/artweb/) can be followed to deploy it.

After the code is deployed, it is necessary to import the included artcmsdb.sql file to MySQL. This creates an initial user for admin and some dummy pages.

The interface can be accessed by opening, <http://localhost:8080/ArtWebCms/webroot/> the webserver URL and folder address can be different based on deployment.

After deploying the copy with database and webserver, the admin interface can be accessed with username: `admin` and password: `admin` and the top menu can be edited by editing the file in `/App/views/home.twig`

### Components
--------------

An initial installation contains,

 - [Symfony](https://symfony.com/) 3 components
 - [FluentPDO](https://github.com/envms/fluentpdo) is used with PDO database abstraction layer
 - [Twig](http://twig.sensiolabs.org/) is used as Template manager
 - [JQuery](https://jquery.com/) and [JQuery UI](https://jqueryui.com/) is used for front end controls including validation
 - Editor formatting with [markdown](https://simplemde.com/)

### License
-----------

The code is released under MIT License.


### Contact
-----------

> **Abdullah Al Zakir Hossain**

- Email:   <aazhbd@yahoo.com>
- Github:   <http://github.com/aazhbd>
- Profile:   <https://de.linkedin.com/in/aazhbd>
