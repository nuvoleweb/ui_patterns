<?php

namespace Drupal\ui_patterns_ds;

/**
 * Field template processor interface.
 *
 * @package Drupal\ui_patterns_ds
 */
interface FieldTemplateProcessorInterface {

  /**
   * Process field template variables.
   *
   * @param array $variables
   *   Variables array.
   *
   * @see template_preprocess_field__pattern_ds_field_template()
   */
  public function process(array &$variables);

}
