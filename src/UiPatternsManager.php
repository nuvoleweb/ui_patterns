<?php

namespace Drupal\ui_patterns;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\ui_patterns\Exception\PatternDefinitionException;

/**
 * Provides the default ui_patterns manager.
 */
class UiPatternsManager extends DefaultPluginManager implements UiPatternsManagerInterface {

  use StringTranslationTrait;

  /**
   * Twig template file extension.
   */
  const TWIG_EXTENSION = '.html.twig';

  /**
   * Pattern prefix.
   */
  const PATTERN_PREFIX = 'pattern_';

  /**
   * The app root.
   *
   * @var string
   */
  protected $root;

  /**
   * Validation service.
   *
   * @var \Drupal\ui_patterns\UiPatternsValidation
   */
  protected $validation;

  /**
   * Provides default values for all ui_patterns plugins.
   *
   * @var array
   */
  protected $defaults = [
    'id' => '',
    'label' => '',
    'description' => '',
    'fields' => [],
    'libraries' => [],
    'extra' => [],
    'base path' => '',
    'use' => '',
    'class' => 'Drupal\ui_patterns\Plugin\UiPatterns\Pattern\Pattern',
  ];

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
   * @param \Twig_Loader_Chain $loader
   *    Twig loader service.
   * @param \Drupal\ui_patterns\UiPatternsValidation $validation
   *    Pattern validation service.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *    Cache backend service.
   */
  public function __construct(\Traversable $namespaces, $root, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, \Twig_Loader_Chain $loader, UiPatternsValidation $validation, CacheBackendInterface $cache_backend) {
    parent::__construct('Plugin/UiPatterns/Pattern', $namespaces, $module_handler, 'Drupal\ui_patterns\UiPatternInterface', 'Drupal\ui_patterns\Annotation\UiPattern');
    $this->root = $root;
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->loader = $loader;
    $this->validation = $validation;
    $this->alterInfo('ui_patterns_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
  }

  /**
   * Get the patterns.
   *
   * @return UiPatternInterface[]
   *   The patterns plugins.
   */
  public function getPatterns() {
    $patterns = [];

    foreach ($this->getDefinitions() as $plugin_id => $definition) {
      $patterns[$definition['id']] = $this->createInstance($plugin_id, $definition);
    }

    return $patterns;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterDefinitions(&$definitions) {
    foreach ($definitions as $id => $definition) {
      try {
        $this->validation->validate($definition);
      }
      catch (PatternDefinitionException $e) {
        unset($definitions[$id]);
        $message = $this->t("Pattern '@id' is skipped because of the following validation error: @message", ['@id' => $id, '@message' => $e->getMessage()]);
        drupal_set_message($message, 'error');
      }
    }

    parent::alterDefinitions($definitions);
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
  public function getPatternFieldsOptions($id) {
    $definition = $this->getDefinition($id);
    return array_map(function ($option) {
      return $option['label'];
    }, $definition['fields']);
  }

  /**
   * {@inheritdoc}
   */
  public function hookTheme() {
    $items = [];

    foreach ($this->getPatterns() as $pattern) {
      $definition = $pattern->definition();
      $items[$definition['theme hook']] = $pattern->hookTheme();
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function hookLibraryInfoBuild() {
    // @codingStandardsIgnoreStart
    $libraries = [];

    foreach ($this->getPatterns() as $pattern) {
      $libraries += $pattern->hookLibraryInfoBuild();
    }

    return $libraries;
    // @codingStandardsIgnoreEnd
  }

  /**
   * {@inheritdoc}
   */
  public function isPatternHook($hook) {
    $patterns = array_filter($this->getPatterns(), function ($pattern) use ($hook) {
      return $pattern->isPatternHook($hook);
    });
    return !empty($patterns);
  }

  /**
   * {@inheritdoc}
   */
  protected function providerExists($provider) {
    return $this->moduleHandler->moduleExists($provider) || $this->themeHandler->themeExists($provider);
  }

}
