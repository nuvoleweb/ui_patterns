<?php

namespace Drupal\ui_patterns_props_widget\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns_props_widget\Plugin\EnumerationPropWidgetBase;

/**
 * Select widget.
 *
 * @PropWidget(
 *   id = "select",
 *   label = @Translation("Select"),
 *   priority = 100,
 *   schema = {
 *     "type": "string",
 *     "enum": {}
 *   }
 * )
 */
class SelectPropWidget extends EnumerationPropWidgetBase {

  /**
   * {@inheritdoc}
   */
  protected function getEnumerationType() {
    return 'select';
  }

}
