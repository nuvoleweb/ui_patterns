<?php

namespace Drupal\ui_patterns;

/**
 * Interface UiPatternsValidationInterface.
 *
 * @package Drupal\ui_patterns
 */
interface UiPatternsValidationInterface {

  /**
   * Validate plugin definition.
   *
   * @param array $definition
   *    Plugin definition.
   *
   * @throws \Drupal\ui_patterns\Exception\PatternDefinitionException
   *    Throw exception if plugin definition is not valid.
   */
  public function validate(array $definition);

}
