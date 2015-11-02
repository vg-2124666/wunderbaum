Wunderbaum VG demo
------------------

"Wunderbaum" is an application which can be used for three things, and three things only:
 * Display two "Top Five!" lists from a varnish log file
 * List recent news items from an RSS feed
 * List recent news items from a json feed


Installing Wunderbaum
---------------------
Wunderbaum uses [Composer](http://getcomposer.org) for php dependencies and requires php version >= 5.6 to run. 

Download and install composer, then (from the git root of this project) run `composer install` to download and 
install all application dependencies and set up the autoloader.


Running Wunderbaum the easy way
-------------------------------
You can run Wunderbaum in development mode with the php built-in web server. Open a console application and
then (from the git root of this project) run `php -S localhost:8000 -t web web/server.php` to load up the
application. Then open up a web browser and navigate to http://localhost:8000/ to see Wunderbaum in action.

You can change the port number when running the built-in web server by replacing `8000` with a port number of
your own choosing. Remember to use the same port number when opening it up in a web browser!


Running Wunderbaum the slightly less easy way
---------------------------------------------
You can run Wunderbaum through nginx, Apache, IIS or any other web server that supports PHP. 
Set up a dedicated hostname for Wunderbaum, then point the web root to the `web/` folder inside the source root,
using `index.php` as the default document.

When running this in a production environment, you will want to remove or make unavailable the `web/server.php` file.


Testing
-------
A very basic test suite can be run by running phpunit, either from an installed phpunit binary, or 
by running `./vendor/phpunit/phpunit/phpunit` (from the git root).


Why "Wunderbaum"?
-----------------
When your car is messy and smelly, Wunderbaum is a small, fresh piece of joy that just makes everything better.
Just like this.


License
-------
All files in this repository is released under the MIT license. See the included license file.
