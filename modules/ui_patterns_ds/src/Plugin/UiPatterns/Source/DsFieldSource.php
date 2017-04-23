<?php

namespace Drupal\ui_patterns_ds\Plugin\UiPatterns\Source;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ds\Plugin\DsPluginManager;
use Drupal\ui_patterns\Plugin\PatternSourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines Display Suite fields source plugin.
 *
 * @UiPatternsSource(
 *   id = "ds_field",
 *   label = @Translation("Display Suite"),
 *   provider = "ds",
 *   tags = {
 *     "entity_display"
 *   }
 * )
 */
class DsFieldSource extends PatternSourceBase implements ContainerFactoryPluginInterface {

  /**
   * Entity manager service.
   *
   * @var \Drupal\ds\Plugin\DsPluginManager
   */
  protected $dsManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DsPluginManager $ds_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dsManager = $ds_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ds')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    $fields = $this->dsManager->getDefinitions();

    foreach ($fields as $field) {
      if (!$this->getContextProperty('limit')) {
        $sources[] = $this->getSourceField($field['id'], $field['title']);
      }
      elseif (in_array($field['id'], $this->getContextProperty('limit'))) {
        $sources[] = $this->getSourceField($field['id'], $field['title']);
      }
    }
    return $sources;
  }

}
