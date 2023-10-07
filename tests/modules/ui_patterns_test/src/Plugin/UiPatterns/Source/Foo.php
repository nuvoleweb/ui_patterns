<?php declare(strict_types = 1);

namespace Drupal\ui_patterns_test\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "foo",
 *   label = @Translation("Foo"),
 *   description = @Translation("Foo description."),
 *   prop_types = {
 *     "string"
 *   }
 * )
 */
final class Foo extends SourcePluginBase {

  public function getData(): mixed {
    return 'foo';
  }

  public function defaultConfiguration() {
    return [];
  }

}
