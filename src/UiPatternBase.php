<?php

namespace Drupal\ui_patterns;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UiPatternBase.
 *
 * @package Drupal\ui_patterns
 */
abstract class UiPatternBase extends PluginBase implements UiPatternInterface, ContainerFactoryPluginInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * The app root.
   *
   * @var string
   */
  protected $root;

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
   * UiPatternBase constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler service.
   * @param string $root
   *   The application root directory.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, $root, array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $module_handler;
    $this->themeHandler = $theme_handler;
    $this->root = $root;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('module_handler'),
      $container->get('theme_handler'),
      $container->get('app.root'),
      $configuration,
      $plugin_id,
      $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function definition() {
    $definition = $this->configuration + $this->defaults;

    $definition['id'] = $this->getPluginId();

    $definition['custom theme hook'] = TRUE;
    if (empty($definition['theme hook'])) {
      $definition['theme hook'] = 'pattern_' . $this->getDerivativeId();
      $definition['custom theme hook'] = FALSE;
    }

    $definition['theme variables'] = array_fill_keys(array_keys($definition['fields']), NULL);
    $definition['theme variables']['attributes'] = [];
    $definition['theme variables']['context'] = [];

    return $definition;
  }

  /**
   * {@inheritdoc}
   */
  public function isPatternHook($hook) {
    $definition = $this->definition();
    return $definition['theme hook'] == $hook;
  }

  /**
   * {@inheritdoc}
   */
  public function javascript() {

  }

  /**
   * {@inheritdoc}
   */
  public function stylesheet() {

  }

  /**
   * {@inheritdoc}
   */
  public function template() {

  }

  /**
   * {@inheritdoc}
   */
  public function hookTheme() {
    $definition = $this->definition();
    $item = [
      'variables' => $definition['theme variables'],
    ];

    $item += $this->processCustomThemeHookProperty($definition);
    $item += $this->processTemplateProperty($definition);
    $item += $this->processUseProperty($definition);
    return $item;
  }

  /**
   * {@inheritdoc}
   */
  public function hookLibraryInfoBuild() {
    // @codingStandardsIgnoreStart
    $definition = $this->definition();
    $libraries = [];

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
      $id = str_replace(':', '_', $id . '.' . key($value));
      $libraries[$id] = reset($value);
    });

    return $libraries;
    // @codingStandardsIgnoreEnd
  }

  /**
   * Process libraries.
   *
   * @param array $libraries
   *    Libraries array.
   * @param string $base_path
   *    Pattern base path.
   */
  private function processLibraries(array &$libraries, $base_path) {
    foreach ($libraries as $name => $values) {
      $is_asset = stristr($name, '.css') !== FALSE || stristr($name, '.js') !== FALSE;
      $is_external = isset($values['type']) && $values['type'] == 'external';
      if ($is_asset && !$is_external) {
        $libraries[$base_path . DIRECTORY_SEPARATOR . $name] = $values;
        unset($libraries[$name]);
      }
      elseif (!$is_asset) {
        $this->processLibraries($libraries[$name], $base_path);
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
    $return = [];
    if (!$definition['custom theme hook'] && $this->moduleHandler->moduleExists($definition['provider'])) {
      /** @var \Drupal\Core\Extension\Extension $module */
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
   * @throws \Twig_Error_Loader
   *    Throws exception if template is not found.
   *
   * @see UiPatternsManager::hookTheme()
   */
  protected function processUseProperty(array $definition) {
    /** @var \Drupal\Core\Extension\Extension $module */
    static $processed = [];

    if (isset($processed[$definition['id']])) {
      throw new \Twig_Error_Loader("Template specified in 'use:' for pattern {$definition['id']} cannot be found (recursion detected).");
    }

    $return = [];
    if (!empty($definition['use'])) {
      $processed[$definition['id']] = TRUE;

      $template = $definition['use'];
      $parts = explode(DIRECTORY_SEPARATOR, $template);
      $name = array_pop($parts);
      $name = str_replace(UiPatternsManager::TWIG_EXTENSION, '', $name);

      $path = $this->twigLoader->getSourceContext($template)->getPath();
      $path = str_replace($this->root . DIRECTORY_SEPARATOR, '', $path);
      $path = str_replace(DIRECTORY_SEPARATOR . $name . UiPatternsManager::TWIG_EXTENSION, '', $path);

      $return = [
        'path' => $path,
        'template' => $name,
      ];
    }
    return $return;
  }

}
