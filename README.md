CakePHP Exercise
================
This is a simple solution for [this CakePHP exercise](https://gist.github.com/uzyn/1e14060a0a28fad08669).

Configuration
-------------
Most of the configuration is CakePHP's configuration. Kindly refer to [CakePHP Book 2.X](http://book.cakephp.org/2.0/en/getting-started.html) for basic configuration. 

Database
---------
By default, this application uses `cake` database, you can change the database used by referring to [CakePHP book](http://book.cakephp.org/2.0/en/getting-started.html).

Installation
------------
Installation is as simple as setting database tables using CakePHP shell, assume you are in the `app` directory:
	sh Console/cake schema create
Once it is configured, point your browser to this application

Administrator
-------------
Admin CakePHP shell command is provided to ease the tasks for managing administrator. To add admininstrator:
	sh Console/cake admin add
To remove an admin:
	sh Console/cake admin remove
To change an admin password:
	sh Console/cake admin passwd
