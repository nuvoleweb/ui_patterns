<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\Pattern;

use Drupal\ui_patterns\UiPatternBase;
use Drupal\ui_patterns\UiPatternInterface;

/**
 * The UI Pattern plugin.
 *
 * @UiPattern(
 *   id = "ui_patterns",
 *   label = @Translation("UI Pattern"),
 *   description = @Translation("TODO"),
 *   deriver = "Drupal\ui_patterns\Plugin\Derivative\UiPatterns\Pattern\Pattern"
 * )
 */
class Pattern extends UiPatternBase implements UiPatternInterface {

}
