<?php

namespace Drupal\ui_patterns_config\Entity;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\file\Entity\File;
use Drupal\ui_patterns_config\UiPatternsConfigInterface;

/**
 * Defines the UI Patterns configuration entity.
 *
 * @ConfigEntityType(
 *   id = "ui_patterns_config",
 *   label = @Translation("pattern configuration"),
 *   handlers = {
 *     "list_builder" = "Drupal\ui_patterns_config\Controller\UiPatternsConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ui_patterns_config\Form\UiPatternsConfigForm",
 *       "edit" = "Drupal\ui_patterns_config\Form\UiPatternsConfigForm",
 *       "delete" = "Drupal\ui_patterns_config\Form\UiPatternsConfigDeleteForm",
 *     }
 *   },
 *   config_prefix = "ui_patterns_config",
 *   admin_permission = "administer UI patterns configuration entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/ui_patterns_config/{ui_patterns_config}",
 *     "delete-form" = "/admin/config/system/ui_patterns_config/{ui_patterns_config}/delete",
 *   }
 * )
 */
class UiPatternsConfig extends ConfigEntityBase implements UiPatternsConfigInterface {

  /**
   * The pattern ID.
   *
   * @var string
   *   The pattern ID.
   */
  public $id;

  /**
   * The pattern label.
   *
   * @var string
   *   The pattern label.
   */
  public $label;

  /**
   * The pattern definition.
   *
   * @var string
   *   The pattern definition.
   */
  public $definition;

  /**
   * The pattern template.
   *
   * @var string
   *   The pattern template.
   */
  public $template;

  /**
   * The pattern stylesheet.
   *
   * @var string
   *   The pattern stylesheet.
   */
  public $stylesheet;

  /**
   * The pattern javascript.
   *
   * @var string
   *   The pattern javascript.
   */
  public $javascript;

  /**
   * Get the pattern definition.
   *
   * @return string
   *   The pattern definition.
   */
  public function definition() {
    return $this->definition;
  }

  /**
   * Get defaults definition.
   *
   * @param string $key
   *   Specify a default key to get from the default definition.
   *
   * @return array|null
   *   The default definition or NULL if the provided argument doesn't exists.
   */
  private function defaults($key = NULL) {
    // @todo: complete this.
    $defaults = [
      'libraries' => [],
      'fields' => [],
      'description' => 'Default plugin description.',
    ];

    return (is_null($key) ? $defaults : (isset($defaults[$key]) ? $defaults[$key] : NULL));
  }

  /**
   * Get the pattern definition processed.
   *
   * @return array
   *   The definition processed.
   */
  public function getProcessedDefinition() {
    $definition = (array) Yaml::decode($this->definition());
    $definition['label'] = $this->label();
    return $definition;
  }

  /**
   * Get the pattern template.
   *
   * @return string
   *   The pattern template.
   */
  public function template() {
    return $this->template;
  }

  /**
   * Get the pattern stylesheet.
   *
   * @return string
   *   The pattern stylesheet.
   */
  public function stylesheet() {
    return $this->stylesheet;
  }

  /**
   * Get the pattern javascript.
   *
   * @return string
   *   The pattern javascript.
   */
  public function javascript() {
    return $this->javascript;
  }

  /**
   * Save pattern data into files.
   *
   * @param string $content
   *   The content of the file.
   * @param string $filename
   *   The filename.
   *
   * @return \Drupal\File\Entity\File
   *   The saved file.
   */
  public function saveToFile($content, $filename) {
    $file = File::create([
      'uid' => 1,
      'filename' => $filename,
      'uri' => 'public://ui_patterns_config/' . $this->id() . '/' . $filename,
      'status' => 1,
    ]);
    $file->save();

    $dir = dirname($file->getFileUri());
    if (!file_exists($dir)) {
      mkdir($dir, 0777, TRUE);
    }
    file_put_contents($file->getFileUri(), $content);
    $file->save();

    return $file;
  }

  /**
   * Save pattern data into files.
   */
  public function savePatternToFiles() {
    $this->saveToFile($this->template(), $this->getTemplateFilename());
    $this->saveToFile($this->javascript(), $this->getJavascriptFilename());
    $this->saveToFile($this->stylesheet(), $this->getStylesheetFilename());
  }

  /**
   * {@inheritdoc}
   */
  public function save() {
    // Save the files.
    $this->savePatternToFiles();
    return parent::save();
  }

  /**
   * {@inheritdoc}
   */
  public static function load($id) {
    // Ensure the pattern files exists.
    self::savePatternToFiles();
    return parent::load($id);
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    Cache::invalidateTags(['ui_patterns']);
    return parent::postSave($storage, $update);
  }

}
