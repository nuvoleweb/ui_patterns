<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;

/**
 * Provides the default ui_patterns manager.
 */
class UiPatternsManager extends DefaultPluginManager implements UiPatternsManagerInterface {

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

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
  ];

  /**
   * Constructs a UiPatternsManager object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, CacheBackendInterface $cache_backend) {
    // Add more services as required.
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
  }

  /**
   * {@inheritdoc}
   */
  protected function providerExists($provider) {
    return $this->moduleHandler->moduleExists($provider) || $this->themeHandler->themeExists($provider);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $directories = $this->moduleHandler->getModuleDirectories() + $this->themeHandler->getThemeDirectories();
      $this->discovery = new YamlDiscovery('ui_patterns', $directories);
      $this->discovery->addTranslatableProperty('label', 'label_context');
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }

    return $this->discovery;
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    $required = [
      'id',
      'label',
      'description',
      'fields',
    ];
    foreach ($required as $name) {
      if (empty($definition[$name])) {
        throw new PluginException(sprintf('UI Pattern plugin property "%s" is required and cannot be empty.', $name));
      }
    }

    if (!is_array($definition['fields'])) {
      throw new PluginException('UI Pattern plugin property "fields" must be an array.');
    }

    if (empty($definition['theme hook'])) {
      $definition['theme hook'] = "pattern__{$plugin_id}";
    }

    $definition['theme variables'] = array_fill_keys(array_keys($definition['fields']), NULL);
    $definition['theme variables']['attributes'] = [];
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionByThemeHook($hook) {
    foreach ($this->getDefinitions() as $definition) {
      if ($definition['theme hook'] == $hook) {
        return $definition;
      }
    }

    throw new PluginException(sprintf('No UI Pattern definition found for theme hook "".', $hook));
  }

}
