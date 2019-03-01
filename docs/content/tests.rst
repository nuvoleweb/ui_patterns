Working with tests
==================

UI Patterns is tested using `PHPUnit in Drupal 8 <https://www.drupal.org/docs/8/phpunit>`_.

To build the test site just run:

.. code-block:: bash

   $ composer install

After that you'll find a fully functional Drupal 8 site under ``./build``, thanks to the integration with the
`OpenEuropa Task Runner <https://github.com/openeuropa/task-runner>`_.

Tu run tests use:

.. code-block:: bash

   $ ./vendor/bin/phpunit

Working with coding standards
=============================

UI Patterns coding standards checks are ran using `GrumPHP <https://github.com/phpro/grumphp>`_.

.. code-block:: bash

   $ ./vendor/bin/grumphp run

Docker Compose
==============

UI Patterns ships with a ``docker-compose.yml`` file which can be used to streamline local development and tests execution.

Setup Docker Compose by copying ``docker-compose.yml`` to ``docker-compose.override.yml`` and replace ``${TRAVIS_PHP_VERSION}``
with the desired PHP version (either "5.6" or "7.1").

After that run:

.. code-block:: bash

   $ docker-compose up -d
   $ docker-compose exec -u www-data php composer install
   $ docker-compose exec -u www-data php ./vendor/bin/run drupal:site-setup
   $ docker-compose exec -u www-data php ./vendor/bin/run drupal:site-install
   $ docker-compose exec -u www-data php chown -R www-data:www-data build

You'll then have a fully functional test site at `http://localhost:8080 <http://localhost:8080>`_.

To run all tests use:

.. code-block:: bash

   $ docker-compose exec -u www-data php ./vendor/bin/grumphp run
   $ docker-compose exec -u www-data php ./vendor/bin/phpunit
