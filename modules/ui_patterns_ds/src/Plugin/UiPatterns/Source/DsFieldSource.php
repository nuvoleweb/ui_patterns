<?php

namespace Drupal\ui_patterns_ds\Plugin\UiPatterns\Source;

use Drupal\ds\Plugin\DsPluginManager;
use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\Core\TypedData\TypedDataManager;
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
class DsFieldSource extends UiPatternsSourceBase {

  /**
   * Entity manager service.
   *
   * @var \Drupal\ds\Plugin\DsPluginManager
   */
  protected $dsManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $ui_patterns_manager, TypedDataManager $typed_data_manager, DsPluginManager $ds_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $ui_patterns_manager, $typed_data_manager);
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
      $container->get('plugin.manager.ui_patterns'),
      $container->get('typed_data_manager'),
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
