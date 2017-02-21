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
   * Get list of pattern libraries, be them locally or globally declared.
   *
   * @return array
   *    List of libraries, suitable for "#attach" render array element.
   */
  public function getLibraries();

}
