<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ui_patterns\Plugin\PatternSourceBase;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\Core\TypedData\TypedDataManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "extra_fields",
 *   label = @Translation("Extra fields"),
 *   provider = "field",
 *   tags = {
 *     "entity_display"
 *   }
 * )
 */
class ExtraFieldSource extends PatternSourceBase implements ContainerFactoryPluginInterface {

  /**
   * Entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $ui_patterns_manager, TypedDataManager $typed_data_manager, EntityFieldManager $entity_field_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityFieldManager = $entity_field_manager;
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
      $container->get('entity_field.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    $entity_type_id = $this->getContextProperty('entity_type');
    $bundle = $this->getContextProperty('entity_bundle');
    $extra_fields = $this->entityFieldManager->getExtraFields($entity_type_id, $bundle);

    if (!isset($extra_fields['display'])) {
      return $sources;
    }

    foreach ($extra_fields['display'] as $extra_field_name => $field) {
      if (!$this->getContextProperty('limit')) {
        $sources[] = $this->getSourceField($extra_field_name, $field['label']);
      }
      elseif (in_array($extra_field_name, $this->getContextProperty('limit'))) {
        $sources[] = $this->getSourceField($extra_field_name, $field['label']);
      }
    }

    return $sources;
  }

}
