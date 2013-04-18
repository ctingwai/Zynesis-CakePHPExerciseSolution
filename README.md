CakePHP Exercise
================
This is a simple solution for [this CakePHP exercise](https://gist.github.com/uzyn/1e14060a0a28fad08669).

Configuration
-------------
Most of the configuration is CakePHP's configuration. Kindly refer to [CakePHP Book 2.X](http://book.cakephp.org/2.0/en/getting-started.html) for basic configuration. 

Database
---------
Please follow the instructions in the previous section to setup the database.

Installation
------------
Installation is as simple as setting database tables using CakePHP shell, assume you are in the `app` directory:
	sh Console/cake schema create
Once it is configured, point your browser to this application.

Some notes on permission
------------------------
Make sure that `app/tmp` and `app/webroot/product_img` is writable by the web server. This might be the issue when u see something about `_cake_core_cache was unable to write` error.

Using CakePHP Shell
-------------------
CakePHP shell require `php` command, make sure that it is in your PATH environment by issuing, assume that you are using lampp:
	$ export PATH=$PATH:/opt/lampp/bin

Some user also reported that they have difficulties using CakePHP shell if they are not in `app/` directory. So make sure that you change directory to `app/`

Product Images
--------------
Product images are stored in `app/webroot/product_img`, you can upload the image directly through the provided UI once logged in or copy the image directly to this directory. The product image is named after the product title, they have to match in order for the application to work.

Default image has to be named `default_no_image_found.png` in `app/webroot/product_img/` directory.

Administrator
-------------
Admin CakePHP shell command is provided to ease the tasks for managing administrator. To add admininstrator:
	sh Console/cake admin add
To remove an admin:
	sh Console/cake admin remove
To change an admin password:
	sh Console/cake admin passwd
