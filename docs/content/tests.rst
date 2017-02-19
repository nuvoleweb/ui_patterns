Working with tests
==================

UI Patterns is tested using both `Behat <http://behat.org/en/latest/>`_ and `PHPUnit <https://phpunit.de/>`_.

In order to work with tests you need a fully functional Drupal site. To build the test site perform the following steps:

- Copy the following files in the directory you wish the test site to be available in:

.. code-block:: bash

   ./tests/behat.yml.dist
   ./tests/composer.json
   ./tests/phpunit.xml.dist

- Run ``composer install`` in the test site directory and install the site.
- Change ``./tests/behat.yml.dist`` and ``./tests/phpunit.xml.dist`` environment-specific parameters.
- Install the site.

After installing the site you need to clone the UI Patterns module into the site's modules directory and enable the
``ui_patterns_test`` module: this module will import all necessary configuration, enable the test theme and expose all
test patterns.

In short the steps above can be summarized as follows:

.. code-block:: bash

   $ git clone https://github.com/nuvoleweb/ui_patterns.git # Or your own fork.
   $ mkdir test-site
   $ cp ui_patterns/tests/composer.json test-site
   $ cp ui_patterns/tests/phpunit.xml.dist test-site
   $ cp ui_patterns/tests/behat.yml.dist test-site
   $ cd test-site
   $ composer install
   $ ./vendor/bin/drush si standard -y --db-url=mysql://USER:PASS@HOST/DATABASE
   $ git clone https://github.com/nuvoleweb/ui_patterns.git modules/ui_patterns # Or your own fork.
   $ ./vendor/bin/drush en ui_patterns_test -y

The test site will then be available in ``./test-site``.

PHPUnit
-------

To execute PHPUnit tests run the following command in the module's root:

.. code-block:: bash

   $ vi phpunit.xml.dist # Change environment-specific parameters.
   $ ./vendor/bin/phpunit

Behat
-----

To run Behat tests perform the following steps:

.. code-block:: bash

   $ vi behat.yml.dist # Change environment-specific parameters.
   $ ./vendor/bin/behat

Working with the test site
--------------------------

When working locally with the target site it might be handy to disable the Twig cache as follows:

 1. Copy ``./sites/example.settings.local.php`` into ``./sites/default/settings.local.php``
 2. Un-comment lines including ``settings.local.php`` in ``./sites/default/settings.php``
 3. Disable Twig cache by adding the following lines to ``./sites/development.services.yml`` and clear the cache.

.. code-block:: yaml

   parameters:
     http.response.debug_cacheability_headers: true
     twig.config:
       debug: true
       auto_reload: true
       cache: false
