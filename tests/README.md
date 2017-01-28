# UI Patterns tests

We use Behat to test UI Patterns. In order to do that we need to have a fully installed Drupal website to run our tests
against. Such website lives in the `tests/drupal` directory and it is built by running `composer install` in `tests`.

In order to test the UI Patterns module we symlink the full repository in a Composer post-install hook to
`tests/drupal/modules/contrib/ui_patterns` (see `tests/composer.json` for more info) so that the target site can
correctly install the module.
 
We also use a test module and a test theme (see `tests/ui_patterns_test` and `tests/ui_patterns_test_theme`).
Both extensions expose patterns and configuration in order to have a fully configured test site.

### Working with target site

When working locally with the target site it might be handy to disable Twig cache as follows:
  
 1. Copy `tests/drupal/sites/example.settings.local.php` into `tests/drupal/sites/default/settings.local.php`
 2. Un-comment lines including `settings.local.php` in `tests/drupal/sites/default/settings.php`
 3. Disable Twig cache by adding the following lines to `tests/drupal/sites/development.services.yml` and clear cache:
 
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
disabling Twig cache by setting the following value into your `tests/drupal/sites/default/settings.local.php`: 

```php
$settings['extension_discovery_scan_tests'] = FALSE;
```
