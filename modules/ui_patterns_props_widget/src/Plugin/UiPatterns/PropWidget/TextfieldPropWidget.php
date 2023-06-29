<?php

namespace Drupal\ui_patterns_props_widget\Plugin\UIPatterns\PropWidget;

use Drupal\ui_patterns_props_widget\Plugin\PropWidgetBase;
use Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition;

/**
 * Textfield widget.
 *
 * @PropWidget(
 *   id = "textfield",
 *   label = @Translation("Textfield"),
 *   priority = 1,
 *   schema = {
 *     "type": "string"
 *   }
 * )
 */
class TextfieldPropWidget extends PropWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function widgetForm(array $form, $value, PropWidgetDefinition $def, $form_type) {
    $form[$def->getName()] = [
      '#type' => 'textfield',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
    ];
    $this->handleInput($form[$def->getName()], $def, $form_type);
    return $form;
  }

}
