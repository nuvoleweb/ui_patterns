<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\PropType;

use Drupal\ui_patterns\PropTypePluginBase;

/**
 * Provides a 'Machine name' PropType.
 *
 * @PropType(
 *   id = "machine_name",
 *   label = @Translation("Machine name"),
 *   description = @Translation("TBD"),
 *   priority = 100,
 *   schema = {
 *     "type": "string",
 *     "pattern": "^[A-Za-z]+\w*$"
 *   }
 * )
 */
class MachineNamePropType extends PropTypePluginBase {

}
