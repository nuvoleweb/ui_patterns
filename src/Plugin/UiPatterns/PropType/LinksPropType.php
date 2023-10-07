<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'links' PropType.
 *
 * @PropType(
 *   id = "links",
 *   label = @Translation("Links"),
 *   description = @Translation("TBD"),
 *   priority = 10,
 *   schema = {
 *     "type": "array",
 *     "items": {
 *       "type": "object",
 *       "properties": {
 *         "title": {"type": "string"},
 *         "attributes": { "$ref": "ui-patterns://attributes" },
 *         "below": { "$ref": "ui-patterns://links" }
 *       }
 *     }
 *   }
 * )
 */
class LinksPropType extends PropTypePluginBase {

}
