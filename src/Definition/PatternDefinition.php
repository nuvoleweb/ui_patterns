<?php

namespace Drupal\ui_patterns\Definition;

use Drupal\Component\Plugin\Definition\PluginDefinition;

/**
 * Class PatternDefinition.
 *
 * @package Drupal\ui_patterns\Definition
 */
class PatternDefinition extends PluginDefinition {

  private $label;
  private $description = '';
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

  /**
   * PatternDefinition constructor.
   */
  public function __construct(array $definition = []) {
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
   * Setter.
   *
   * @param mixed $id
   *    Property value.
   *
   * @return $this
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * Getter.
   *
   * @return mixed
   *   Property value.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Setter.
   *
   * @param mixed $label
   *   Property value.
   *
   * @return $this
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * Getter.
   *
   * @return mixed
   *   Property value.
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * Setter.
   *
   * @param mixed $basePath
   *   Property value.
   *
   * @return $this
   */
  public function setBasePath($basePath) {
    $this->basePath = $basePath;
    return $this;
  }

  /**
   * Getter.
   *
   * @return mixed
   *   Property value.
   */
  public function getFileName() {
    return $this->fileName;
  }

  /**
   * Setter.
   *
   * @param mixed $fileName
   *   Property value.
   *
   * @return $this
   */
  public function setFileName($fileName) {
    $this->fileName = $fileName;
    return $this;
  }

  /**
   * Setter.
   *
   * @param mixed $provider
   *   Property value.
   *
   * @return $this
   */
  public function setProvider($provider) {
    $this->provider = $provider;
    return $this;
  }

  /**
   * Getter.
   *
   * @return PatternDefinitionField[]
   *   Property value.
   */
  public function getFields() {
    return $this->fields;
  }

  /**
   * Setter.
   *
   * @param array $fields
   *   Property value.
   *
   * @return $this
   */
  public function setFields(array $fields) {
    foreach ($fields as $value) {
      $field = new PatternDefinitionField($value['name'], $value['label']);
      if (isset($value['type'])) {
        $field->setType($value['type']);
      }
      if (isset($value['description'])) {
        $field->setDescription($value['description']);
      }
      $this->fields[$value['name']] = $field;
    }
    return $this;
  }

  /**
   * Set field.
   *
   * @param string $name
   *    Field name.
   * @param string $label
   *    Field label.
   *
   * @return $this
   */
  public function setField($name, $label) {
    $this->fields[$name] = new PatternDefinitionField($name, $label);
    return $this;
  }

  /**
   * Check whereas field exists.
   *
   * @param string $name
   *   Field name.
   *
   * @return bool
   *   Whereas field exists
   */
  public function hasField($name) {
    return isset($this->fields[$name]);
  }

  /**
   * Get field.
   *
   * @param string $name
   *    Field name.
   *
   * @return PatternDefinitionField|null
   *    Definition field.
   */
  public function getField($name) {
    return $this->hasField($name) ? $this->fields[$name] : NULL;
  }

  /**
   * Getter.
   *
   * @return string
   *   Property value.
   */
  public function getThemeHook() {
    return $this->themeHook;
  }

  /**
   * Setter,.
   *
   * @param string $themeHook
   *   Property value.
   *
   * @return $this
   */
  public function setThemeHook($themeHook) {
    $this->themeHook = $themeHook;
    return $this;
  }

  /**
   * Getter.
   *
   * @return string
   *   Property value.
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Setter.
   *
   * @param string $description
   *   Property value.
   *
   * @return $this
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * Getter.
   *
   * @return string
   *   Property value.
   */
  public function getUse() {
    return $this->use;
  }

  /**
   * Setter.
   *
   * @param string $use
   *   Property value.
   *
   * @return $this
   */
  public function setUse($use) {
    $this->use = $use;
    return $this;
  }

  /**
   * Getter.
   *
   * @return array
   *   Property value.
   */
  public function getTags() {
    return $this->tags;
  }

  /**
   * Setter.
   *
   * @param array $tags
   *   Property value.
   *
   * @return $this
   */
  public function setTags($tags) {
    $this->tags = $tags;
    return $this;
  }

  /**
   * Getter.
   *
   * @return mixed
   *   Property value.
   */
  public function getCustomThemeHook() {
    return $this->customThemeHook;
  }

  /**
   * Setter.
   *
   * @param mixed $customThemeHook
   *   Property value.
   *
   * @return $this
   */
  public function setCustomThemeHook($customThemeHook) {
    $this->customThemeHook = $customThemeHook;
    return $this;
  }

  /**
   * Getter.
   *
   * @return mixed
   *   Property value.
   */
  public function getTemplate() {
    return $this->template;
  }

  /**
   * Setter.
   *
   * @param mixed $template
   *   Property value.
   *
   * @return $this
   */
  public function setTemplate($template) {
    $this->template = $template;
    return $this;
  }

  /**
   * Getter.
   *
   * @return mixed
   *   Property value.
   */
  public function getLibraries() {
    return $this->libraries;
  }

  /**
   * Setter.
   *
   * @param mixed $libraries
   *   Property value.
   *
   * @return $this
   */
  public function setLibraries($libraries) {
    $this->libraries = $libraries;
    return $this;
  }

}
