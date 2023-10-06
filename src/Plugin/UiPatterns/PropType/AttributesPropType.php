<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'Attributes' PropType.
 *
 * @PropType(
 *   id = "attributes",
 *   label = @Translation("Attributes"),
 *   description = @Translation("TBD"),
 *   priority = 10,
 *   schema = {
 *     "type": "object",
 *     "patternProperties": {
 *       ".+": {
 *         "anyOf": {
 *           {
 *             "type": {"string", "number"}
 *           },
 *           {
 *             "type": "array",
 *             "items": {
 *               "anyOf": {{ "type": "number" }, { "type": "string" }}
 *             }
 *           }
 *         }
 *       }
 *     }
 *   }
 * )
 */
class AttributesPropType extends PropTypePluginBase {

}
