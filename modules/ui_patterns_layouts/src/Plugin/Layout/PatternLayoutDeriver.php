<?php

namespace Drupal\ui_patterns_layouts\Plugin\Layout;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PatternLayoutDeriver.
 *
 * @package Drupal\ui_patterns_layouts\Plugin\Layout
 */
class PatternLayoutDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManagerInterface
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    // @todo: use DI after following issue gets in.
    // @link https://www.drupal.org/node/2868949
    $this->manager = \Drupal::service('plugin.manager.ui_patterns');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('plugin.manager.ui_patterns')
    );
  }

  /**
   * Gets the definition of all derivatives of a base plugin.
   *
   * @param \Drupal\Core\Layout\LayoutDefinition|array $base_plugin_definition
   *   The definition array of the base plugin.
   *
   * @return \Drupal\Core\Layout\LayoutDefinition[]
   *   An array of full derivative definitions keyed on derivative id.
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach ($this->manager->getDefinitions() as $pattern_definition) {
      $definition = clone $base_plugin_definition;

      // @codingStandardsIgnoreStart
      $definition->setLabel(new TranslatableMarkup($pattern_definition['label']));
      $definition->setThemeHook($pattern_definition['theme hook']);
      $definition->set('pattern', $pattern_definition['id']);
      $definition->set('provider', $pattern_definition['provider']);
      $regions = [];
      foreach ($pattern_definition['fields'] as $field) {
        $regions[$field['name']]['label'] = $field['label'];
      }
      $definition->setRegions($regions);

      if (isset($pattern_definition['description'])) {
        $definition->setDescription(new TranslatableMarkup($pattern_definition['description']));
      }

      $this->derivatives[$pattern_definition['id']] = $definition;
      // @codingStandardsIgnoreEnd
    }

    return $this->derivatives;
  }

}
