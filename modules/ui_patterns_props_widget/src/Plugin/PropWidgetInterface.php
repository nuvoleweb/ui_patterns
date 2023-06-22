<?php

namespace Drupal\ui_patterns_props_widget\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition;

/**
 * Defines an interface for PropWidget plugins.
 */
interface PropWidgetInterface extends ConfigurableInterface {

  /**
   * Returns the configuration form elements specific to this widget plugin.
   *
   * @param array $form
   *   The form definition array for the settings configuration form.
   * @param string $value
   *   The stored default value.
   * @param \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition $def
   *   The widget definition.
   * @param string $form_type
   *   The form type. Either layout or layouts_display or display.
   *
   * @return array
   *   The configuration form.
   */
  public function widgetForm(array $form, $value, PropWidgetDefinition $def, $form_type);

  /**
   * Preprocess props.
   *
   * @param string $value
   *   The stored value.
   * @param array $context
   *   Context informations.
   *   Keys:
   *    - entity.
   * @param \Drupal\ui_patterns_props_widget\Definition\PropWidgetDefinition $def
   *   The prop widget definition.
   *
   * @return string
   *   The processed value.
   */
  public function propPreprocess($value, array $context, PropWidgetDefinition $def);

  /**
   * Returns the processed variable.
   *
   * @param string $value
   *   The stored value.
   * @param array $context
   *   Context informations.
   *
   * @return mixed
   *   The processed value.
   */
  public function preprocess($value, array $context);

  /**
   * Returns the settings configuration form.
   *
   * @param array $form
   *   The form definition array for the settings configuration form.
   * @param string $value
   *   The stored default value.
   * @param string $token_value
   *   The stored token value.
   * @param string $form_type
   *   The form type. Either layout or layouts_display or display.
   */
  public function buildConfigurationForm(array $form, $value, $token_value, $form_type);

}
