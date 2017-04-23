<?php

namespace Drupal\ui_patterns_views\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\PatternSourceBase;

/**
 * Defines Views row pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "views_row",
 *   label = @Translation("Views row"),
 *   provider = "views",
 *   tags = {
 *     "views_row"
 *   }
 * )
 */
class ViewsRowSource extends PatternSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    /** @var \Drupal\views\ViewExecutable $view */
    $view = $this->getContextProperty('view');
    foreach ($view->display_handler->getFieldLabels() as $name => $label) {
      $sources[] = $this->getSourceField($name, $label);
    }
    return $sources;
  }

}
