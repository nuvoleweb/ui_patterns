<?php

namespace Drupal\ui_patterns_config\Plugin\UiPatterns\Pattern;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\ui_patterns\UiPatternBase;
use Drupal\ui_patterns\UiPatternInterface;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The UI Pattern Config plugin.
 *
 * @UiPattern(
 *   id = "ui_patterns_config",
 *   label = @Translation("UI Pattern Config"),
 *   description = @Translation("TODO"),
 *   deriver = "Drupal\ui_patterns_config\Plugin\Derivative\UiPatterns\Pattern\UiPatternConfig"
 * )
 */
class UiPatternConfig extends UiPatternBase implements UiPatternInterface {

  /**
   * Twig loader service.
   *
   * @var \Twig_LoaderInterface
   */
  protected $twigLoader;

  /**
   * UiPatternConfig constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler service.
   * @param \Twig_Loader_Filesystem $twig_loader
   *   The twig loader filesystem service.
   * @param string $root
   *   The application root directory.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler, \Twig_Loader_Filesystem $twig_loader, $root, array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($module_handler, $theme_handler, $root, $configuration, $plugin_id, $plugin_definition);
    $this->twigLoader = $twig_loader;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('module_handler'),
      $container->get('theme_handler'),
      $container->get('twig.loader.filesystem'),
      $container->get('app.root'),
      $configuration,
      $plugin_id,
      $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function definition() {
    $definition = parent::definition();

    // Add the path to Twig.
    $name = 'ui_patterns_config';
    $path = drupal_realpath('public://ui_patterns_config');
    $this->twigLoader->addPath($path, $name);

    // Fiddle variables to get this pattern working properly.
    $definition['theme hook'] = $this->getDerivativeId();
    $definition['custom theme hook'] = TRUE;
    $definition['base path'] = drupal_realpath('public://ui_patterns_config/' . $this->getDerivativeId());;
    $definition['provider'] = 'ui_patterns_config';
    $definition['use'] = '@ui_patterns_config/' . $this->getDerivativeId() . '/' . $this->getTemplateFilename();

    // Generate libraries.
    $definition['libraries'] += [
      [
        UiPatternsManager::PATTERN_PREFIX . $this->getDerivativeId() => [
          'css' => [
            'component' => [
              $this->getStylesheetFilename() => [],
            ],
          ],
          'js' => [
            $this->getJavascriptFilename() => [],
          ],
        ],
      ],
    ];

    return $definition;
  }

  /**
   * Get the pattern template filename.
   *
   * @return string
   *   The pattern template filename.
   */
  private function getTemplateFilename() {
    return $this->getTemplateName() . UiPatternsManager::TWIG_EXTENSION;
  }

  /**
   * Get the pattern javascript filename.
   *
   * @return string
   *   The pattern javascript filename.
   */
  private function getJavascriptFilename() {
    return $this->getTemplateName() . '.js';
  }

  /**
   * Get the pattern stylesheet filename.
   *
   * @return string
   *   The pattern stylesheet filename.
   */
  private function getStylesheetFilename() {
    return $this->getTemplateName() . '.css';
  }

  /**
   * Get the pattern template name.
   *
   * @return string
   *   The pattern template name.
   */
  private function getTemplateName() {
    return str_replace('_', '-', UiPatternsManager::PATTERN_PREFIX . $this->getDerivativeId());
  }

}
