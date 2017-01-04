<?php

namespace Drupal\ui_patterns_field_group\Plugin\field_group\FieldGroupFormatter;

use Drupal\field_group\FieldGroupFormatterInterface;

/**
 * Interface ConfigurableFieldGroupFormatterInterface.
 *
 * @package Drupal\ui_patterns\Plugin\field_group\FieldGroupFormatter
 */
interface ConfigurableFieldGroupFormatterInterface extends FieldGroupFormatterInterface {

  /**
   * Massage the form values to get the desired configuration.
   *
   * This method can be used to fix form values before saving them
   * or to initialise a plugin directly from form values.
   *
   * @param array $settings
   *   The submitted form values passed by reference.
   */
  public static function transformFormToSettings(&$settings);

}
