<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns_views\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source.
 *
 * @Source(
 *   id = "view_title",
 *   label = @Translation("View title"),
 *   description = @Translation("TBD."),
 *   prop_types = {
 *     "slot"
 *   }
 * )
 */
final class ViewTitleSource extends SourcePluginBase {

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
