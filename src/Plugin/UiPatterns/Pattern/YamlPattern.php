<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\Pattern;

use Drupal\ui_patterns\UiPatternBase;
use Drupal\ui_patterns\UiPatternInterface;

/**
 * The UI Pattern plugin.
 *
 * @UiPattern(
 *   id = "yaml",
 *   label = @Translation("YAML Pattern"),
 *   description = @Translation("Pattern defined using a YAML file."),
 *   deriver = "\Drupal\ui_patterns\Plugin\Deriver\YamlDeriver"
 * )
 */
class YamlPattern extends UiPatternBase implements UiPatternInterface {

}
