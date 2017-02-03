<?php

namespace Drupal\ui_patterns_config\Plugin\UiPatterns\Pattern;

use Drupal\ui_patterns\UiPatternBase;
use Drupal\ui_patterns\UiPatternInterface;

/**
 * The UI Pattern Config plugin.
 *
 * @UiPattern(
 *   id = "pattern_my_annotation_custom",
 *   label = @Translation("My Annotation Custom Plugin"),
 *   description = @Translation("A simple custom pattern"),
 *   deriver = "Drupal\ui_patterns_config\Plugin\Derivative\UiPatterns\Pattern\UiPatternConfig"
 * )
 */
class UiPatternConfig extends UiPatternBase implements UiPatternInterface {

}
