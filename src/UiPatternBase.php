<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;

/**
 * Class UiPatternBase.
 *
 * @package Drupal\ui_patterns
 */
abstract class UiPatternBase extends PluginBase implements UiPatternInterface {

  /**
   * Prefix for locally defined libraries.
   */
  const LIBRARY_PREFIX = 'ui_patterns';

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->getPluginDefinition()['id'];
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->getPluginDefinition()['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function hasField($name) {
    return isset($this->getFields()[$name]);
  }

  /**
   * {@inheritdoc}
   */
  public function getField($name) {
    $field = [];
    if ($this->hasField($name)) {
      $field = $this->getFields()[$name];
    }
    return $field;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldType($name) {
    return $this->getFieldProperty($name, 'type');
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldLabel($name) {
    return $this->getFieldProperty($name, 'label');
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldPreview($name) {
    return $this->getFieldProperty($name, 'preview');
  }

  /**
   * {@inheritdoc}
   */
  public function hasCustomThemeHook() {
    return $this->getPluginDefinition()['custom theme hook'];
  }

  /**
   * {@inheritdoc}
   */
  public function getThemeHook() {
    return $this->getPluginDefinition()['theme hook'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFields() {
    return $this->getPluginDefinition()['fields'];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries() {
    $libraries = [];
    foreach ($this->getPluginDefinition()['libraries'] as $library) {
      if (is_array($library)) {
        $libraries[] = self::LIBRARY_PREFIX . '/' . $this->getId() . '.' . key($library);
      }
      else {
        $libraries[] = $library;
      }
    }
    return $libraries;
  }

  /**
   * {@inheritdoc}
   */
  public function hasUse() {
    $definition = $this->getPluginDefinition();
    return !empty($definition['use']);
  }

  /**
   * {@inheritdoc}
   */
  public function getUse() {
    return $this->getPluginDefinition()['use'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsAsOptions() {
    $options = [];
    foreach ($this->getFields() as $field) {
      $options[$field['name']] = $field['label'];
    }
    return $options;
  }

  /**
   * Get field property.
   *
   * @param string $field
   *    Field name.
   * @param string $name
   *    Field property name.
   * @param mixed $default
   *    Default value if field property not found.
   *
   * @return mixed
   *    Field property value.
   */
  protected function getFieldProperty($field, $name, $default = NULL) {
    $value = $default;
    if ($this->hasField($field) && isset($this->getFields()[$field][$name])) {
      $value = $this->getFields()[$field][$name];
    }
    return $value;
  }

}
