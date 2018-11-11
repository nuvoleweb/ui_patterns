<?php

namespace Drupal\ui_patterns_library\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ui_patterns\Definition\PatternDefinition;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PatternLibraryController.
 *
 * @package Drupal\ui_patterns\Controller
 */
class PatternsLibraryController extends ControllerBase {

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
    return $this->patternsManager->getDefinition($name)->getLabel();
  }

  /**
   * Render pattern library page.
   *
   * @param string $name
   *   Plugin ID.
   *
   * @return array
   *   Return render array.
   */
  public function single($name) {
    $definition = $this->patternsManager->getDefinition($name);

    return [
      '#theme' => 'patterns_single_page',
      '#pattern' => [
        'meta' => [
          '#theme' => 'patterns_meta_information',
          '#pattern' => $definition->toArray(),
        ],
        'rendered' => $this->getPatternRenderArray($definition),
        'definition' => $definition->toArray(),
      ],
    ];
  }

  /**
   * Render pattern library page.
   *
   * @return array
   *   Patterns overview page render array.
   */
  public function overview() {

    $patterns = [];
    foreach ($this->patternsManager->getDefinitions() as $definition) {
      $patterns[$definition->id()] = $definition->toArray() + [
        'meta' => [
          '#theme' => 'patterns_meta_information',
          '#pattern' => $definition->toArray(),
        ],
        'rendered' => $this->getPatternRenderArray($definition),
        'definition' => $definition->toArray(),
      ];
    }

    return [
      '#theme' => 'patterns_overview_page',
      '#patterns' => $patterns,
    ];
  }

  /**
   * Get pattern preview render array, handling variants.
   *
   * @param \Drupal\ui_patterns\Definition\PatternDefinition $definition
   *   Pattern definition object.
   *
   * @return array
   *   Render array.
   */
  protected function getPatternRenderArray(PatternDefinition $definition) {
    $render = [];

    // If pattern has variants then render them all adding meta information
    // on top of each one, or simply render pattern preview otherwise.
    if ($definition->hasVariants()) {
      foreach ($definition->getVariants() as $variant) {
        $render[$definition->id() . '_' . $variant->getName()] = [
          'meta' => [
            '#theme' => 'patterns_variant_meta_information',
            '#variant' => $variant->toArray(),
          ],
          'pattern' => [
            '#type' => 'pattern_preview',
            '#id' => $definition->id(),
            '#variant' => $variant->getName(),
            '#theme_wrappers' => [
              'container' => [
                '#attributes' => ['class' => 'pattern-preview__markup pattern-preview__markup--variant_' . $variant->getName()],
              ],
            ],
          ],
        ];
      }
    }
    else {
      $render[$definition->id()] = [
        'pattern' => [
          '#type' => 'pattern_preview',
          '#id' => $definition->id(),
          '#theme_wrappers' => [
            'container' => [
              '#attributes' => ['class' => 'pattern-preview__markup'],
            ],
          ],
        ],
      ];
    }

    return $render;
  }

}
