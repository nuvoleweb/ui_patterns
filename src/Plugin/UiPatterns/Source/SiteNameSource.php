<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "sitename",
 *   label = @Translation("Sitename"),
 *   description = @Translation("Foo description."),
 *   prop_types = {
 *     "string"
 *   }
 * )
 */
final class SiteNameSource extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return 'Nice Site name';
  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
