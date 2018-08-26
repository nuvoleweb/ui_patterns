<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides the default ui_patterns manager.
 *
 * @method \Drupal\ui_patterns\Definition\PatternDefinition getDefinition($plugin_id, $exception_on_invalid = TRUE)
 */
class UiPatternsManager extends DefaultPluginManager implements PluginManagerInterface {

  use StringTranslationTrait;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * An array of pattern theme hooks for fast lookup on not cached pages.
   *
   * @var array
   */
  protected $patternHooks = [];

  /**
   * UiPatternsManager constructor.
   */
  public function __construct(\Traversable $namespaces, ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, CacheBackendInterface $cache_backend) {
    parent::__construct('Plugin/UiPatterns/Pattern', $namespaces, $module_handler, 'Drupal\ui_patterns\Plugin\PatternInterface', 'Drupal\ui_patterns\Annotation\UiPattern');
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->alterInfo('ui_patterns_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
  }

  /**
   * Get pattern objects.
   *
   * @return \Drupal\ui_patterns\Plugin\PatternBase[]
   *   Pattern objects.
   */
  public function getPatterns() {
    $patterns = [];
    foreach ($this->getDefinitions() as $definition) {
      $patterns[] = $this->getFactory()->createInstance($definition->id());
    }
    return $patterns;
  }

  /**
   * Return pattern definitions.
   *
   * @return \Drupal\ui_patterns\Definition\PatternDefinition[]
   *   Pattern definitions.
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
  public function isPatternHook($hook) {
    // Improve performance on not cached pages.
    if (empty($this->patternHooks)) {
      foreach ($this->getDefinitions() as $definition) {
        $this->patternHooks[$definition->getThemeHook()] = $definition->getThemeHook();
      }
    }
    return !empty($this->patternHooks[$hook]);
  }

  /**
   * {@inheritdoc}
   */
  protected function providerExists($provider) {
    return $this->moduleHandler->moduleExists($provider) || $this->themeHandler->themeExists($provider);
  }

}
