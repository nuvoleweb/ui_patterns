<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UiPatternBase.
 *
 * @package Drupal\ui_patterns
 */
abstract class UiPatternBase extends PluginBase implements UiPatternInterface, ContainerFactoryPluginInterface {

  /**
   * Prefix for locally defined libraries.
   */
  const LIBRARY_PREFIX = 'ui_patterns';

  /**
   * The app root.
   *
   * @var string
   */
  protected $root;

  /**
   * Typed data manager service.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * UiPatternsManager constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $root, TypedDataManager $typed_data_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->root = $root;
    $this->typedDataManager = $typed_data_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('app.root'),
      $container->get('typed_data_manager')
    );
  }

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

  /**
   * {@inheritdoc}
   */
  public function getLibraryDefinitions() {
    // @codingStandardsIgnoreStart
    $libraries = [];
    $definition = $this->getPluginDefinition();

    // Get only locally defined libraries.
    $items = array_filter($definition['libraries'], function ($library) {
      return is_array($library);
    });

    // Attach pattern base path to assets.
    if (!empty($definition['base path'])) {
      $base_path = str_replace($this->root, '', $definition['base path']);
      $this->processLibraries($items, $base_path);
    }

    // Produce final libraries array.
    $id = $definition['id'];
    array_walk($items, function ($value) use (&$libraries, $id) {
      $libraries[$id . '.' . key($value)] = reset($value);
    });

    // @codingStandardsIgnoreEnd
    return $libraries;
  }

  /**
   * Process libraries.
   *
   * @param array $libraries
   *    Libraries array.
   * @param string $base_path
   *    Pattern base path.
   * @param string $parent
   *    Item parent set in previous recursive iteration, if any.
   */
  protected function processLibraries(array &$libraries, $base_path, $parent = '') {
    $parents = ['js', 'base', 'layout', 'component', 'state', 'theme'];
    $_libraries = $libraries;
    foreach ($_libraries as $name => $values) {
      $is_asset = in_array($parent, $parents, TRUE);
      $is_external = isset($values['type']) && $values['type'] == 'external';
      if ($is_asset && !$is_external) {
        $libraries[$base_path . DIRECTORY_SEPARATOR . $name] = $values;
        unset($libraries[$name]);
      }
      elseif (!$is_asset) {
        $this->processLibraries($libraries[$name], $base_path, $name);
      }
    }
  }

}
