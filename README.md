# ArtWebCMS

ArtWebCMS is a fast, secure, feature rich, scalable Content Management System, powered by ArtWeb using Symfony2 components integrating with the best practices of MVC.

![ArtWebCMS](http://www.articulatedlogic.com/media/images/artwebcms.height-320.png)

### Features
------------

 - A comprehensive Admin panel that enables,
     - Page creation, editing and deletion with selected category, using markdown.
     - Category creation, edition and deletion.
     - User management with password update and add, edit more users.
     - Allows creating basic websites and blogs with high number of pages with high control.
 - User login and signup process included
 - The initial setup is very small but scalable. It can be used for very large project with more components easily.
 - The project can be extended by adding any number of components, installed and maintained with ```composer```.
 - More static libraries and components can be added in project specific static container.
 - The code and structure adheres to the proven best practices of OOP and MVC, creating highly maintainable code.
 - The operation flow and points of execution makes debugging easier.
 - The Route manager is flexible and mimics functionalities of proven flexibility of development such as Django.
 - It contains a flexible and expendable Configuration manager that handles system values along with custom user values.
 - The data manager is adaptable to any other ORM or SQL builder with any DBMS, including MySQL, PostgreSQL, SQLite etc.

### Requirements
----------------

Requires `PHP` or newer, `composer 7` or newer

### Install instructions
------------------------

Since ArtWebCMS is based on [ArtWeb](http://articulatedlogic.com/a/artweb/), the same [steps](http://articulatedlogic.com/a/artweb/) can be followed to deploy it.

The repository contains appropriate docker configuration to deploy the development system.
Assuming the docker is installed and available on the command line, the following commands can be used
to deploy a running system:

```console
$ git clone https://github.com/aazhbd/ArtWeb.git && cd ArtWeb
$ docker-compose up
```

Once the installation is complete, the home page can be accessed by opening ```http://localhost:8080/```

After deploying the copy with database and webserver, the admin interface can be accessed with
 username: `admin` and password: `admin` and the top menu can be edited by editing the file in `/App/views/home.twig`

An initial database and example contents are available in the ```data_source/artcmsdb.sql``` file, which is imported during provision if the database is created through docker.

### Components
--------------

An initial installation contains,

 - [Symfony](https://symfony.com/) components
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
