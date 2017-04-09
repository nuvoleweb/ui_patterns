<?php

namespace Drupal\ui_patterns;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\TypedData\TypedDataManager;

/**
 * Provides the default ui_patterns manager.
 */
class UiPatternsManager extends DefaultPluginManager implements UiPatternsManagerInterface {

  use StringTranslationTrait;

  /**
   * The app root.
   *
   * @var string
   */
  protected $root;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * Typed data manager service.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * UiPatternsManager constructor.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param string $root
   *    Application root directory.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *    Module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *    Theme handler service.
   * @param \Drupal\Core\TypedData\TypedDataManager $typed_data_manager
   *    Typed data manager service.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *    Cache backend service.
   */
  public function __construct(\Traversable $namespaces, $root, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, TypedDataManager $typed_data_manager, CacheBackendInterface $cache_backend) {
    parent::__construct('Plugin/UiPatterns/Pattern', $namespaces, $module_handler, 'Drupal\ui_patterns\UiPatternInterface', 'Drupal\ui_patterns\Annotation\UiPattern');
    $this->root = $root;
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->typedDataManager = $typed_data_manager;
    $this->alterInfo('ui_patterns_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
  }

  /**
   * {@inheritdoc}
   */
  public function getPattern($id) {
    // @todo should we cache pattern object instances?
    return $this->getFactory()->createInstance($id);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions() {
    $definitions = $this->getCachedDefinitions();
    if (!isset($definitions)) {
      // Remove derivative id from pattern definitions keys.
      // @todo: make sure validation takes care of ensuring ids are unique.
      $definitions = [];
      foreach ($this->findDefinitions() as $id => $definition) {
        $definitions[$definition['id']] = $definition;
        unset($definitions[$id]);
      }
      $this->setCachedDefinitions($definitions);
    }
    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getPatternsOptions() {
    return array_map(function ($option) {
      return $option['label'];
    }, $this->getDefinitions());
  }

  /**
   * {@inheritdoc}
   */
  public function hookTheme() {
    $items = [];

    foreach ($this->getDefinitions() as $definition) {
      $item = [];
      $hook = $definition['theme hook'];

      foreach ($definition['fields'] as $field) {
        $item['variables'][$field['name']] = NULL;
      }
      $item['variables']['attributes'] = [];
      $item['variables']['context'] = [];
      $item['variables']['use'] = '';

      $item += $this->processUseProperty($definition);
      $item += $this->processCustomThemeHookProperty($definition);
      $item += $this->processTemplateProperty($definition);
      $items[$hook] = $item;
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function hookLibraryInfoBuild() {
    // @codingStandardsIgnoreStart
    $libraries = [];
    foreach ($this->getDefinitions() as $definition) {

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
    }

    // @codingStandardsIgnoreEnd
    return $libraries;
  }

  /**
   * {@inheritdoc}
   */
  public function isPatternHook($hook) {
    $definitions = array_filter($this->getDefinitions(), function ($definition) use ($hook) {
      return $definition['theme hook'] == $hook;
    });
    return !empty($definitions);
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

  /**
   * Process 'custom hook theme' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   *
   * @see UiPatternsManager::hookTheme()
   */
  protected function processCustomThemeHookProperty(array $definition) {
    /** @var \Drupal\Core\Extension\Extension $module */
    $return = [];
    if (!$definition['custom theme hook'] && $this->moduleHandler->moduleExists($definition['provider'])) {
      $module = $this->moduleHandler->getModule($definition['provider']);
      $return['path'] = $module->getPath() . '/templates';
    }
    return $return;
  }

  /**
   * Process 'template' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   *
   * @see UiPatternsManager::hookTheme()
   */
  protected function processTemplateProperty(array $definition) {
    $return = [];
    if (isset($definition['template'])) {
      $return = ['template' => $definition['template']];
    }
    return $return;
  }

  /**
   * Process 'use' definition property.
   *
   * @param array $definition
   *    Pattern definition array.
   *
   * @return array
   *    Processed hook definition portion.
   *
   * @see UiPatternsManager::hookTheme()
   */
  protected function processUseProperty(array $definition) {
    $return = [];
    if (!empty($definition['use'])) {
      $return = [
        'path' => $this->moduleHandler->getModule('ui_patterns')->getPath() . '/templates',
        'template' => 'patterns-use-wrapper',
      ];
    }
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  protected function providerExists($provider) {
    return $this->moduleHandler->moduleExists($provider) || $this->themeHandler->themeExists($provider);
  }

}
