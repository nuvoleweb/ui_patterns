<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'Color' PropType.
 *
 * @PropType(
 *   id = "color",
 *   label = @Translation("Color"),
 *   description = @Translation("TBD"),
 *   priority = 10,
 *   schema = {
 *     "type": "string",
 *     "pattern": "^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
 *   }
 * )
 */
class ColorPropType extends PropTypePluginBase {

}
