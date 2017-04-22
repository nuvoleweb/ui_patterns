<?php

namespace Drupal\ui_patterns\Plugin\DataType;

use Drupal\Core\TypedData\Plugin\DataType\Map;

/**
 * The "ui_patterns_pattern" data type.
 *
 * @ingroup typed_data
 *
 * @deprecated
 *
 * @DataType(
 *   id = "ui_patterns_pattern",
 *   label = @Translation("UI Patterns: Pattern"),
 *   definition_class = "\Drupal\ui_patterns\Plugin\DataType\PatternDefinition"
 * )
 */
class Pattern extends Map implements PatternInterface {

  /**
   * Pattern prefix.
   */
  const PATTERN_PREFIX = 'pattern_';

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE) {

    // Set default values.
    $values = $values + [
      'libraries' => [],
      'fields' => [],
      'custom theme hook' => TRUE,
    ];
    parent::setValue($values, FALSE);

    // Process values array.
    $this->processFieldNames();
    $this->processThemeProperties($values);

    // Notify the parent of any changes.
    if ($notify && isset($this->parent)) {
      $this->parent->onChange($this->name);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function isValid() {
    return $this->validate()->count() == 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrorMessages() {
    $messages = [];
    /** @var \Symfony\Component\Validator\ConstraintViolationList $violations */
    $violations = $this->validate();
    foreach ($violations as $violation) {
      $messages[] = $this->t('Validation error on ":id.:property": :message', [
        ':id' => $this->get('id')->getValue(),
        ':property' => $violation->getPropertyPath(),
        ':message' => $violation->getMessage(),
      ]);
    }
    return $messages;
  }

  /**
   * Process theme-related properties.
   *
   * @param array $values
   *    Current values.
   */
  private function processThemeProperties(array $values) {
    if (!isset($values['theme hook'])) {
      $this->values['theme hook'] = self::PATTERN_PREFIX . $this->values['id'];
      $this->values['custom theme hook'] = FALSE;
    }
  }

  /**
   * Explicitly Set field 'name' property.
   */
  private function processFieldNames() {
    foreach ($this->values['fields'] as $name => $value) {
      $this->values['fields'][$name]['name'] = $name;
    }
  }

}
