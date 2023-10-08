<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'enum' PropType.
 *
 * @PropType(
 *   id = "enum",
 *   label = @Translation("Enum"),
 *   description = @Translation("TBD"),
 *   priority = 10,
 *   schema = {
 *     "type": {"string", "number", "integer"},
 *     "enum": {},
 *   }
 * )
 */
class EnumPropType extends PropTypePluginBase {

}
