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
    $type = [];
    if ($this->hasField($name)) {
      $type = $this->getFields()[$name]['type'];
    }
    return $type;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldLabel($name) {
    $label = [];
    if ($this->hasField($name)) {
      $label = $this->getFields()[$name]['label'];
    }
    return $label;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldPreview($name) {
    $preview = NULL;
    if ($this->hasField($name) && isset($this->getFields()[$name]['preview'])) {
      $preview = $this->getFields()[$name]['preview'];
    }
    return $preview;
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

}
