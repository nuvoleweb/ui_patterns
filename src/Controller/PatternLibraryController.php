<?php

namespace Drupal\ui_patterns\Controller;

use Drupal\Core\Controller\ControllerBase;
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
   * {@inheritdoc}
   */
  public function __construct(UiPatternsManager $ui_patterns_manager) {
    $this->patternsManager = $ui_patterns_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.ui_patterns'));
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
    $pattern = $this->patternsManager->createInstance($name, $this->patternsManager->getDefinition($name));
    //$definition = $pattern->definition();
    $definition['rendered']['#type'] = 'pattern_preview';
    $definition['rendered']['#id'] = $pattern->getPluginId();
    $definition['meta']['#theme'] = 'patterns_meta_information';
    $definition['meta']['#pattern'] = $pattern->definition();

    return [
      '#theme' => 'patterns_single_page',
      '#pattern' => $definition,
    ];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Patterns overview page render array.
   */
  public function overview() {
    $patterns = $this->patternsManager->getPatterns();

    foreach ($patterns as $pattern) {
      // @todo: Fix this.
      $definitions[$pattern->getPluginId()] = $pattern->definition();
      $definitions[$pattern->getPluginId()]['rendered']['#type'] = 'pattern_preview';
      $definitions[$pattern->getPluginId()]['rendered']['#id'] = $pattern->getPluginId();
      $definitions[$pattern->getPluginId()]['meta']['#theme'] = 'patterns_meta_information';
      $definitions[$pattern->getPluginId()]['meta']['#pattern'] = $pattern->definition();
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $definitions,
    ];
  }

}
