<?php

namespace Drupal\ui_patterns_props_widget\Plugin;

use Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition;

/**
 * Base class for enumerations like radios or select.
 */
abstract class EnumerationPropWidgetBase extends  PropWidgetBase {

  /**
   * Returns empty option.
   *
   * @return array
   *   The empty option.
   */
  protected function emptyOption() {
    return ["" => $this->t("Please select")];
  }

  /**
   * Returns the enumeration type.
   *
   * @return string
   *   The enumeration type.
   */
  abstract protected function getEnumerationType();

  /**
   * Returns the enumeration options.
   *
   * @param \Drupal\ui_patterns_settings\Definition\PatternDefinitionSetting $def
   *  The pattern definition.
   *
   * @return mixed
   *   The options.
   */
  protected function getOptions(PropWidgetDefinition $def) {
    return $def->getOptions();
  }

  /**
   * {@inheritdoc}
   */
  public function widgetForm(array $form, $value, PropWidgetDefinition $def, $form_type) {
    if ($def->getRequired() == FALSE) {
      $options = $this->emptyOption();
    }
    else {
      $options = [];
    }

    $options += $this->getOptions($def);
    $form[$def->getName()] = [
      '#type' => $this->getEnumerationType($def),
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#options' => $options,
    ];
    $this->handleInput($form[$def->getName()], $def, $form_type);
    return $form;
  }

}
