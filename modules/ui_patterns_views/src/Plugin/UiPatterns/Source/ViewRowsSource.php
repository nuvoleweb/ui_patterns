<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns_views\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "view_rows",
 *   label = @Translation("View rows"),
 *   description = @Translation("TBD."),
 *   prop_types = {
 *     "slot"
 *   }
 * )
 */
final class ViewRowsSource extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    return [];
  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
