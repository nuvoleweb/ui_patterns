<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns_views\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\SourcePluginBase;

/**
 * Plugin implementation of the source.
 *
 * @Source(
 *   id = "view_row",
 *   label = @Translation("View row"),
 *   description = @Translation("TBD."),
 *   prop_types = {
 *     "slot"
 *   }
 * )
 */
final class ViewRowSource extends SourcePluginBase {

  /**
   *
   */
  public function getData(): mixed {
    // $view = $this->getContextProperty('view');
    // foreach ($view->display_handler->getFieldLabels() as $name => $label) {
    //  $sources[] = $this->getSourceField($name, $label);
    // }
    return [];
  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
