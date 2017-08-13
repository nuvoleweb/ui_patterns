<?php

namespace Drupal\ui_patterns\Plugin\UIPatterns\SettingType;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\ui_patterns\Plugin\PatternSettingTypeBase;


/**
 * Checkbox setting type.
 *
 * @UiPatternsSettingType(
 *   id = "boolean",
 *   label = @Translation("true/false")
 * )
 */
class BooleanSettingType extends PatternSettingTypeBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, $value) {

    $def = $this->getPatternSettingDefinition();
    $value = $this->getValue($value);
    $form[$def->getName()] = array(
      '#type' => 'select',
      '#title' => $def->getLabel(),
      '#description' => $def->getDescription(),
      '#default_value' => $value,
      '#required' => $def->getRequired(),
      '#options' =>
        [0 => $this->t('false'), 1 => $this->t('true')]
    );
    return $form;
  }

}
