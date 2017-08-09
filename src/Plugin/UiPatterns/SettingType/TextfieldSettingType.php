<?php

namespace Drupal\ui_patterns\Plugin\UIPatterns\SettingType;

use Drupal\ui_patterns\Plugin\PatternSettingTypeBase;


/**
 * Textfield setting type.
 *
 * @UiPatternsSettingType(
 *   id = "textfield",
 *   label = @Translation("Textfield")
 * )
 */
class TextfieldSettingType extends PatternSettingTypeBase {


  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, $value) {

    $def = $this->getPatternSettingDefinition();
    $form[$def->getName()] = array(
      '#type' => 'textfield',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $this->getValue($value),
      '#required' => $def->getRequired(),
    );
    return $form;
  }
}
