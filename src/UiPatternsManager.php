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
    $this->setCacheBackend($cache_backend, 'ui_patterns', ['ui_patterns']);
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
  public function getDefinitionByThemeHook($hook) {
    foreach ($this->getDefinitions() as $definition) {
      if ($definition['theme hook'] == $hook) {
        return $definition;
      }
    }

    throw new PluginException("No UI Pattern definition found for theme hook '{$hook}'.");
  }

  /**
   * {@inheritdoc}
   */
  public function renderExample($pattern_id) {
    $rendered = [];
    $definition = $this->getDefinition($pattern_id);
    try {
      $rendered = $this->themeManager->render($definition['theme hook'], $this->getExampleVariables($definition['id']));
    }
    catch (\Twig_Error_Loader $e) {
      drupal_set_message($e->getRawMessage(), 'error');
    }
    return $rendered;
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
   * Get variables for given pattern function.
   *
   * @param string $name
   *    Pattern name, i.e. its theme function.
   *
   * @return array
   *    Variables array.
   */
  protected function getExampleVariables($name) {
    $variables = [];
    $definition = $this->getDefinition($name);
    foreach ($definition['fields'] as $name => $field) {
      // Some fields are used as twig array keys and don't need escaping.
      if (!isset($field['escape']) || $field['escape'] != FALSE) {
        // The examples are not user submitted and are safe markup.
        $field['example'] = self::getExampleMarkup($field['example']);
      }

      $variables[$name] = $field['example'];
    }

    if (isset($definition['extra']['attributes'])) {
      $variables['attributes'] = $definition['extra']['attributes'];
    }

    return $variables;
  }

  /**
   * Make safe markup out of the example strings.
   *
   * @param string|string[] $example
   *   The example, may be a string or an array.
   *
   * @return array|\Drupal\Component\Render\MarkupInterface|string
   *   The safe markup of the example
   */
  protected static function getExampleMarkup($example) {
    if (is_array($example)) {
      // Check to see if the example is a render array.
      if (array_key_exists('theme', $example) || array_key_exists('type', $example)) {
        $rendered = [];
        foreach ($example as $key => $value) {
          $rendered['#' . $key] = $value;
        }

        return $rendered;
      }

      // Recursively escape the string elements.
      return array_map([self::class, __METHOD__], $example);
    }

    return Markup::create($example);
  }

}
