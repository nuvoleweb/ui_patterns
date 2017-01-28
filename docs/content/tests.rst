Working with tests
==================

UI Patterns is tested using both `Behat <http://behat.org/en/latest/>`_ and `PHPUnit <https://phpunit.de/>`_.

Both test suites uses a fully functional Drupal site to run tests against. To build the test site perform the following
steps in the module's root:

.. code-block:: bash

   $ composer install
   $ cd tests
   $ composer install
   $ cd drupal
   $ ../vendor/bin/drush si standard -y --db-url=mysql://USER:PASS@HOST/DATABASE
   $ ../vendor/bin/drush en ui_patterns_test -y

The test site will then be available in ``./tests/drupal``.

In order to test the UI Patterns module itself we symlink the full repository in a Composer post-install hook to
``./tests/drupal/modules/contrib/ui_patterns`` so that the target site can correctly install the module.
See ``./tests/composer.json`` for more info.

We also use a test module and a test theme, available respectively in ``./tests/ui_patterns_test`` and
``./tests/ui_patterns_test_theme``, both extensions expose test patterns and test configuration and are great
resources to discover UI Patterns' possibilities.

PHPUnit
-------

To execute PHPUnit tests run the following command in the module's root:

.. code-block:: bash

   $ ./vendor/bin/phpunit --bootstrap ./tests/vendor/autoload.php tests/src/PhpUnit

Behat
-----

To run Behat tests perform the following steps:

 1. Copy ``./behat.yml.dist`` in ``./behat.yml`` and change its parameters according to your local setup.
 2. Run: ``./vendor/bin/behat``

Working with the test site
--------------------------

When working locally with the target site it might be handy to disable the Twig cache as follows:

 1. Copy ``./tests/drupal/sites/example.settings.local.php`` into ``./tests/drupal/sites/default/settings.local.php``
 2. Un-comment lines including ``settings.local.php`` in ``./tests/drupal/sites/default/settings.php``
 3. Disable Twig cache by adding the following lines to ``./tests/drupal/sites/development.services.yml`` and clear
    the cache.

.. code-block:: yaml

   parameters:
     http.response.debug_cacheability_headers: true
     twig.config:
       debug: true
       auto_reload: true
       cache: false


**ATTENTION**

Since the repository symlinks itself in the target site you MUST disable test directory scanning when
disabling Twig cache by setting the following value into your ``./tests/drupal/sites/default/settings.local.php``:

.. code-block:: php

   $settings['extension_discovery_scan_tests'] = FALSE;

