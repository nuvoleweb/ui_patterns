<?php

namespace Drupal\ui_patterns;

/**
 * Interface UiPatternInterface.
 *
 * @package Drupal\ui_patterns
 */
interface UiPatternInterface {

  /**
   * Get pattern ID.
   *
   * @return string
   *    Pattern ID.
   */
  public function getId();

  /**
   * Get pattern label.
   *
   * @return string
   *    Pattern label.
   */
  public function getLabel();

  /**
   * Check if pattern has given field.
   *
   * @param string $name
   *    Field machine name.
   *
   * @return bool
   *    Whereas pattern has field given or not.
   */
  public function hasField($name);

  /**
   * Get pattern field.
   *
   * @param string $name
   *    Field machine name.
   *
   * @return array
   *    Get field array, if any.
   */
  public function getField($name);

  /**
   * Get pattern field type.
   *
   * @param string $name
   *    Field machine name.
   *
   * @return string
   *    Get field type.
   */
  public function getFieldType($name);

  /**
   * Get pattern field label.
   *
   * @param string $name
   *    Field machine name.
   *
   * @return array
   *    Field label.
   */
  public function getFieldLabel($name);

  /**
   * Get pattern field preview, if any.
   *
   * @param string $name
   *    Field machine name.
   *
   * @return mixed
   *    Field preview value, if any.
   */
  public function getFieldPreview($name);

  /**
   * Check if pattern has custom theme hook.
   *
   * @return bool
   *    Whereas pattern has custom theme hook or not.
   */
  public function hasCustomThemeHook();

  /**
   * Get pattern theme hook.
   *
   * @return string
   *    Theme hook.
   */
  public function getThemeHook();

  /**
   * Get pattern fields.
   *
   * @return array
   *    Array of fields keyed by field name.
   */
  public function getFields();

  /**
   * Return list of given pattern fields to be used as select options.
   *
   * @return array
   *    List of pattern fields.
   */
  public function getFieldsAsOptions();

  /**
   * Get list of pattern libraries, be them locally or globally declared.
   *
   * @return array
   *    List of libraries, suitable for "#attach" render array element.
   */
  public function getLibraries();

  /**
   * Check if pattern has the 'use' property defined.
   *
   * @return bool
   *    Whereas pattern has the 'use' property defined.
   */
  public function hasUse();

  /**
   * Return 'use' property value.
   *
   * @return string
   *    The 'use' property value.
   */
  public function getUse();

  /**
   * Build and return Drupal theme implementation for current pattern.
   *
   * @return array
   *    Theme implementation.
   *
   * @see ui_patterns_theme()
   */
  public function getThemeImplementation();

  /**
   * Get library definitions for current pattern.
   *
   * @return array
   *   List of library definitions.
   *
   * @see hook_library_info_build()
   * @see ui_patterns_library_info_build()
   */
  public function getLibraryDefinitions();

}
