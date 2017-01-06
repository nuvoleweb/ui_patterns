<?php

namespace Drupal\ui_patterns\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Theme\ThemeManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PatternLibraryController.
 *
 * @package Drupal\ui_patterns\Controller
 */
class PatternLibraryController extends ControllerBase {

  /**
   * Patterns manager service.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * Theme manager service.
   *
   * @var \Drupal\Core\Theme\ThemeManager
   */
  protected $themeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(UiPatternsManager $ui_patterns_manager, ThemeManager $theme_manager) {
    $this->themeManager = $theme_manager;
    $this->patternsManager = $ui_patterns_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.ui_patterns'),
      $container->get('theme.manager')
    );
  }

  /**
   * Title callback.
   *
   * @return string
   *   Pattern label.
   */
  public function title($name) {
    $definition = $this->patternsManager->getDefinition($name);
    return $definition['label'];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {

    $definition = $this->patternsManager->getDefinition($name);
    $definition['rendered'] = $this->patternsManager->renderPreview($name);
    $definition['meta'] = $this->themeManager->render('patterns_meta_information', ['pattern' => $definition]);

    return [
      '#theme' => 'patterns_single_page',
      '#pattern' => $definition,
    ];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Return render array.
   */
  public function overview() {

    $definitions = $this->patternsManager->getDefinitions();
    foreach ($definitions as $name => $definition) {
      $definitions[$name]['rendered'] = $this->patternsManager->renderPreview($name);
      $definitions[$name]['meta'] = $this->themeManager->render('patterns_meta_information', ['pattern' => $definition]);
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $definitions,
    ];
  }

}
