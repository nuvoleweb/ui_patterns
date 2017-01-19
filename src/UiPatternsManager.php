<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\Core\Theme\ThemeManager;
use Drupal\Core\Render\Markup;

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
   * Theme manager service.
   *
   * @var \Drupal\Core\Theme\ThemeManager
   */
  protected $themeManager;

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
  public function __construct(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, ThemeManager $theme_manager, CacheBackendInterface $cache_backend) {
    // Add more services as required.
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->themeManager = $theme_manager;
    $this->alterInfo('ui_patterns_info');
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);
    self::validateDefinition($definition);

    $definition['custom theme hook'] = TRUE;
    if (empty($definition['theme hook'])) {
      $definition['theme hook'] = "pattern__{$plugin_id}";
      $definition['custom theme hook'] = FALSE;
    }

    $definition['theme variables'] = array_fill_keys(array_keys($definition['fields']), NULL);
    $definition['theme variables']['attributes'] = [];
  }

  /**
   * {@inheritdoc}
   */
  public function renderPreview($pattern_id) {
    $rendered = [];
    $definition = $this->getDefinition($pattern_id);
    try {
      $rendered = [
        '#type' => 'pattern',
        '#id' => $definition['id'],
        '#fields' => $this->getPreviewVariables($definition['id']),
      ];
    }
    catch (\Twig_Error_Loader $e) {
      drupal_set_message($e->getRawMessage(), 'error');
    }
    return $rendered;
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

    foreach ($this->getDefinitions() as $definition) {
      $hook = $definition['theme hook'];
      $items[$hook] = [
        'variables' => $definition['theme variables'],
      ];

      if (!$definition['custom theme hook'] && $this->moduleHandler->moduleExists($definition['provider'])) {
        /** @var \Drupal\Core\Extension\Extension $module */
        $module = $this->moduleHandler->getModule($definition['provider']);
        $items[$hook]['path'] = $module->getPath() . '/templates';
      }
    }

    return $items;
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
      $directories = $this->themeHandler->getThemeDirectories() + $this->moduleHandler->getModuleDirectories();
      $this->discovery = new YamlDiscovery('ui_patterns', $directories);
      $this->discovery->addTranslatableProperty('label', 'label_context');
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }

    return $this->discovery;
  }

  /**
   * Get variables for given pattern function.
   *
   * @param string $name
   *    Pattern name, i.e. its theme function.
   *
   * @return array
   *    Variables array.
   */
  protected function getPreviewVariables($name) {
    $variables = [];
    $definition = $this->getDefinition($name);
    foreach ($definition['fields'] as $name => $field) {
      // Some fields are used as twig array keys and don't need escaping.
      if (!isset($field['escape']) || $field['escape'] != FALSE) {
        // The examples are not user submitted and are safe markup.
        $field['preview'] = self::getPreviewMarkup($field['preview']);
      }

      $variables[$name] = $field['preview'];
    }

    if (isset($definition['extra']['attributes'])) {
      $variables['attributes'] = $definition['extra']['attributes'];
    }

    return $variables;
  }

  /**
   * Make previews markup safe.
   *
   * @param string|string[] $preview
   *   The preview, may be a string or an array.
   *
   * @return array|\Drupal\Component\Render\MarkupInterface|string
   *   Preview safe markup.
   */
  protected static function getPreviewMarkup($preview) {
    if (is_array($preview)) {
      // Check if preview is a render array.
      if (array_key_exists('theme', $preview) || array_key_exists('type', $preview)) {
        $rendered = [];
        foreach ($preview as $key => $value) {
          $rendered['#' . $key] = $value;
        }

        return $rendered;
      }

      // Recursively escape the string elements.
      return array_map([self::class, __METHOD__], $preview);
    }

    return Markup::create($preview);
  }

  /**
   * Validate plugin definition.
   *
   * @param array $definition
   *    Plugin definition.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *    Throw exception if plugin definition is not valid.
   */
  public static function validateDefinition(array $definition) {

    self::assertMachineName($definition['id']);
    foreach (['id', 'label', 'description', 'fields'] as $key) {
      self::assertSetAndNotEmpty($definition, $key);
    }
    self::assertArray('fields', $definition['fields']);

    foreach ($definition['fields'] as $id => $field) {
      self::assertMachineName($id);
      self::assertArray($id, $field);
      foreach (['type', 'label', 'description', 'preview'] as $key) {
        self::assertSetAndNotEmpty($field, $key);
      }
    }
  }

  /**
   * Assert key is set and not empty on target array.
   *
   * @param array $target
   *    Target array.
   * @param string $key
   *    Test key.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *    Throw exception if plugin definition is not valid.
   */
  public static function assertSetAndNotEmpty(array $target, $key) {
    if (!isset($target[$key]) || empty($target[$key])) {
      throw new PluginException(sprintf('UI Pattern plugin property "%s" is required and cannot be not set nor empty.', $key));
    }
  }

  /**
   * Assert valid machine name.
   *
   * @param string $name
   *    Target name.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *    Throw exception if plugin definition is not valid.
   */
  public static function assertMachineName($name) {
    if (preg_match('@[^a-z0-9_]+@', $name)) {
      throw new PluginException(sprintf('UI Pattern ID "%s" must contain only lowercase letters, numbers, and hyphens.', $name));
    }
  }

  /**
   * Assert array.
   *
   * @param string $name
   *    Target name.
   * @param mixed $target
   *    Target.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *    Throw exception if plugin definition is not valid.
   */
  public static function assertArray($name, $target) {
    if (!is_array($target)) {
      throw new PluginException(sprintf('UI Pattern plugin property "%s" must be an array.', $name));
    }
  }

}
