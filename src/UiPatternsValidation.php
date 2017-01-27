<?php

namespace Drupal\ui_patterns;

use Drupal\ui_patterns\Exception\PatternDefinitionException;

/**
 * Class UiPatternsValidation.
 *
 * @package Drupal\ui_patterns
 */
class UiPatternsValidation implements UiPatternsValidationInterface {

  /**
   * {@inheritdoc}
   */
  public function validate(array $definition) {

    $this->assertMachineName($definition['id']);
    foreach (['id', 'label', 'description', 'fields'] as $key) {
      $this->assertSetAndNotEmpty($definition, $key);
    }
    $this->assertArray('fields', $definition['fields']);

    foreach ($definition['fields'] as $id => $field) {
      $this->assertMachineName($id);
      $this->assertAllowedFieldName($id);
      $this->assertArray($id, $field);
      foreach (['type', 'label', 'description'] as $key) {
        $this->assertSetAndNotEmpty($field, $key);
      }
    }
  }

  /**
   * Assert key is set and not empty on target array.
   *
   * @param array $target
   *    Target array.
   * @param string $key
   *    Test key.
   *
   * @throws \Drupal\ui_patterns\Exception\PatternDefinitionException
   *    Throw exception if plugin definition is not valid.
   */
  public function assertSetAndNotEmpty(array $target, $key) {
    if (!isset($target[$key]) || empty($target[$key])) {
      throw new PatternDefinitionException(sprintf('UI Pattern plugin property "%s" is required and cannot be not set nor empty.', $key));
    }
  }

  /**
   * Assert valid machine name.
   *
   * @param string $name
   *    Target name.
   *
   * @throws \Drupal\ui_patterns\Exception\PatternDefinitionException
   *    Throw exception if plugin definition is not valid.
   */
  public function assertMachineName($name) {
    if (preg_match('@[^a-z0-9_]+@', $name)) {
      throw new PatternDefinitionException(sprintf('UI Pattern ID "%s" must contain only lowercase letters, numbers, and hyphens.', $name));
    }
  }

  /**
   * Assert valid field name.
   *
   * @param string $name
   *    Field name.
   *
   * @throws \Drupal\ui_patterns\Exception\PatternDefinitionException
   *    Throw exception if plugin definition is not valid.
   */
  public function assertAllowedFieldName($name) {
    $not_allowed = ['id', 'type', 'theme', 'context'];
    if (in_array($name, $not_allowed)) {
      throw new PatternDefinitionException(sprintf('UI Pattern field name not be one of the following reserved keywords: %s.', implode(', ', $not_allowed)));
    }
  }

  /**
   * Assert array.
   *
   * @param string $name
   *    Target name.
   * @param mixed $target
   *    Target.
   *
   * @throws \Drupal\ui_patterns\Exception\PatternDefinitionException
   *    Throw exception if plugin definition is not valid.
   */
  public function assertArray($name, $target) {
    if (!is_array($target)) {
      throw new PatternDefinitionException(sprintf('UI Pattern plugin property "%s" must be an array.', $name));
    }
  }

}
