<?php

namespace Drupal\ui_patterns\Plugin\DataType;

use Drupal\Core\TypedData\Plugin\DataType\Map;

/**
 * The "ui_patterns_pattern" data type.
 *
 * @ingroup typed_data
 *
 * @DataType(
 *   id = "ui_patterns_pattern",
 *   label = @Translation("UI Patterns: Pattern"),
 *   definition_class = "\Drupal\ui_patterns\Plugin\DataType\PatternDefinition"
 * )
 */
class Pattern extends Map {

  /**
   * Pattern prefix.
   */
  const PATTERN_PREFIX = 'pattern_';

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE) {
    parent::setValue($values, FALSE);
    $this->setNameProperties('fields');
    $this->setNameProperties('variants');

    $this->values['custom theme hook'] = TRUE;
    if (!isset($values['theme hook'])) {
      $this->values['theme hook'] = self::PATTERN_PREFIX . $this->values['id'];
      $this->values['custom theme hook'] = FALSE;
    }

    if (empty($this->values['libraries'])) {
      $this->values['libraries'] = [];
    }

    // Notify the parent of any changes.
    if ($notify && isset($this->parent)) {
      $this->parent->onChange($this->name);
    }
  }

  /**
   * Set name property to array item key.
   *
   * @param string $parent
   *    Parent key to be processed.
   */
  private function setNameProperties($parent) {
    if (isset($this->values[$parent]) && is_array($this->values[$parent])) {
      foreach ($this->values[$parent] as $name => $value) {
        $this->values[$parent][$name]['name'] = $name;
      }
    }
  }

  /**
   * Check whereas the pattern definition is valid or not.
   *
   * @return bool
   *    Whereas the pattern definition is valid or not.
   */
  public function isValid() {
    return $this->validate()->count() == 0;
  }

  /**
   * Get validation error messages.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup[]
   *    List of validation error messages.
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

}
