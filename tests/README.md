# UI Patterns tests

We use Behat to test UI Patterns. In order to do that we need to have a fully installed
Drupal website to run our tests against. Such website lives in the `tests/target/drupal`
directory and it is built by running `composer install` in `tests/target`.

In order to test the module itself we symlink the full repository in a post composer install hook 
in `tests/target/drupal/modules/contrib/ui_patterns` (see `tests/target/composer.json` for more info)
so that the target site can correctly install the module.
 
We also use two custom test extensions:
 
 1. `tests/target/custom/ui_patterns_test`: exposes test patterns and default configuration in order
    to have a fully configured site up-and-running.
 2. `tests/target/custom/ui_patterns_test_theme`: overrides Bartik theme for a cleaner test UI.
    It also exposes custom patterns template files allowing us to test special pattern override cases.

The two extensions above are symlinked in the correct locations by composer when running `composer install`,
see `tests/target/composer.json` for more info.

### Working with target site

When working locally with the target site it might be handy to disable Twig cache as follows:
  
 1. Copy `tests/target/drupal/sites/example.settings.local.php` into `tests/target/drupal/sites/default/settings.local.php`
 2. Un-comment lines including `settings.local.php` in `tests/target/drupal/sites/default/settings.php`
 3. Disable Twig cache by adding the following lines to `tests/target/drupal/sites/development.services.yml` and clear
    the cache:
 
```yaml
parameters:
  http.response.debug_cacheability_headers: true
  twig.config:
    debug: true
    auto_reload: true
    cache: false
``` 

**ATTENTION**

Since the repository symlinks itself in the target site you MUST disable test directory scanning when
disabling Twig cache by setting the following value into your `tests/target/drupal/sites/default/settings.local.php`: 

```php
$settings['extension_discovery_scan_tests'] = FALSE;
```
