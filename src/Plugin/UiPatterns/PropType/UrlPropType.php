<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'Url' PropType.
 *
 * @PropType(
 *   id = "url",
 *   label = @Translation("Url"),
 *   description = @Translation("TBD"),
 *   priority = 10,
 *   schema = {
 *     "type": "string",
 *     "format": "iri-reference"
 *   }
 * )
 */
class UrlPropType extends PropTypePluginBase {

}
