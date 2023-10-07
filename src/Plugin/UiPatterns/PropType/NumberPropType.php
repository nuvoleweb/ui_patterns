<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'number' PropType.
 *
 * @PropType(
 *   id = "number",
 *   label = @Translation("Number"),
 *   description = @Translation("TBD"),
 *   priority = 1,
 *   schema = {
 *     "type": {"number", "integer"}
 *   }
 * )
 */
class NumberPropType extends PropTypePluginBase {

}
