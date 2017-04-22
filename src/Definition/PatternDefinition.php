<?php

namespace Drupal\ui_patterns\Definition;

use Drupal\Component\Plugin\Definition\PluginDefinition;

class PatternDefinition extends PluginDefinition {

  private $id;
  private $label;
  private $description = '';
  private $provider;
  private $basePath;
  private $fileName;
  private $fields = [];
  private $use;
  private $themeHook;
  private $customThemeHook;
  private $template;
  private $libraries = [];
  private $tags;
  private $additional = [];

  public function __construct(array $definition) {
    foreach ($definition as $property => $value) {
      $this->set($property, $value);
    }
  }

  /**
   * Sets a value to an arbitrary property.
   *
   * @param string $property
   *   The property to use for the value.
   * @param mixed $value
   *   The value to set.
   *
   * @return $this
   */
  public function set($property, $value) {
    $property = lcfirst(str_replace(' ', '', ucwords($property)));
    if (property_exists($this, $property)) {
      $this->{$property} = $value;
    }
    else {
      $this->additional[$property] = $value;
    }
    return $this;
  }

  /**
   * @param mixed $id
   * @return PatternDefinition
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @param mixed $label
   * @return PatternDefinition
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * @param mixed $basePath
   * @return PatternDefinition
   */
  public function setBasePath($basePath) {
    $this->basePath = $basePath;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFileName() {
    return $this->fileName;
  }

  /**
   * @param mixed $fileName
   * @return PatternDefinition
   */
  public function setFileName($fileName) {
    $this->fileName = $fileName;
    return $this;
  }

  /**
   * @param mixed $provider
   * @return PatternDefinition
   */
  public function setProvider($provider) {
    $this->provider = $provider;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFields() {
    return $this->fields;
  }

  /**
   * @param array $fields
   * @return PatternDefinition
   */
  public function setFields(array $fields) {
    $this->fields = $fields;
    return $this;
  }

  /**
   * @return string
   */
  public function getThemeHook() {
    return $this->themeHook;
  }

  /**
   * @param string $themeHook
   * @return PatternDefinition
   */
  public function setThemeHook($themeHook) {
    $this->themeHook = $themeHook;
    return $this;
  }

  /**
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * @param string $description
   * @return PatternDefinition
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * @return string
   */
  public function getUse() {
    return $this->use;
  }

  /**
   * @param string $use
   * @return PatternDefinition
   */
  public function setUse($use) {
    $this->use = $use;
    return $this;
  }

  /**
   * @return array
   */
  public function getTags() {
    return $this->tags;
  }

  /**
   * @param array $tags
   * @return PatternDefinition
   */
  public function setTags($tags) {
    $this->tags = $tags;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCustomThemeHook() {
    return $this->customThemeHook;
  }

  /**
   * @param mixed $customThemeHook
   * @return PatternDefinition
   */
  public function setCustomThemeHook($customThemeHook) {
    $this->customThemeHook = $customThemeHook;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getTemplate() {
    return $this->template;
  }

  /**
   * @param mixed $template
   * @return PatternDefinition
   */
  public function setTemplate($template) {
    $this->template = $template;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getLibraries() {
    return $this->libraries;
  }

  /**
   * @param mixed $libraries
   * @return PatternDefinition
   */
  public function setLibraries($libraries) {
    $this->libraries = $libraries;
    return $this;
  }

}
