<?php

namespace Drupal\ui_patterns\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns\Plugin\PatternSettingTypeBase;


/**
 * Checkbox setting type.
 *
 * @UiPatternsSettingType(
 *   id = "checkbox",
 *   label = @Translation("Checkboxes")
 * )
 */
class CheckboxSettingType extends PatternSettingTypeBase {


  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, $value) {

    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    if (is_scalar($value)) {
      $value = [$value];
    }
    $form[$def->getName()] = array(
      '#type' => 'checkboxes',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $value,
      '#required' => $def->getRequired(),
      '#options' => $def->getOptions()
    );
    return $form;
  }

}
