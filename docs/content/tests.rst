Working with tests
==================

UI Patterns is tested using both `Behat <http://behat.org/en/latest/>`_ and `PHPUnit <https://phpunit.de/>`_.

In order to work with tests you need a fully functional Drupal site. To build the test site just run:

.. code-block:: bash

   $ composer install

Thanks to the integration with `Drupal Component Scaffold <https://github.com/nuvoleweb/drupal-component-scaffold>`_
you'll find a fully functional Drupal 8 site under ``./build``.

After that proceed with the site installation and setup:

.. code-block:: bash

  - ./vendor/bin/drush --root=$PWD/build si standard -y --db-url=mysql://YOUR_USER:YOUR_PASS@127.0.0.1/YOUR_DATABASE
  - ./vendor/bin/drush --root=$PWD/build en ui_patterns_test -y

PHPUnit
-------

Setup and run PHPUnit tests by running:

.. code-block:: bash

   $ cp phpunit.xml.dist phpunit.xml # Then change environment-specific parameters.
   $ ./vendor/bin/phpunit

Behat
-----

Setup and run Behat tests by running:

.. code-block:: bash

   $ vi behat.yml.dist behat.yml # Then change environment-specific parameters.
   $ ./vendor/bin/behat

Working with the test site
--------------------------

`Drupal Component Scaffold <https://github.com/nuvoleweb/drupal-component-scaffold>`_ will setup a working development site but
you'll still need to manually disable Twig and other Drupal 8 caching by un-commenting the following lines in your
``settings.php`` file:

.. code-block:: php

   # if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
   #   include $app_root . '/' . $site_path . '/settings.local.php';
   # }

