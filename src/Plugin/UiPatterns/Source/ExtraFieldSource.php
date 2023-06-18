<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ui_patterns\Plugin\PatternSourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "extra_fields",
 *   label = @Translation("Extra fields"),
 *   provider = "core",
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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityFieldManager $entity_field_manager) {
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

    try {
      $limit = $this->getContextProperty('limit');
    }
    catch (PluginException $e) {
      $limit = array_keys($extra_fields['display']);
    }

    foreach ($extra_fields['display'] as $extra_field_name => $field) {
      if (in_array($extra_field_name, $limit)) {
        $sources[] = $this->getSourceField($extra_field_name, $field['label']);
      }
    }

    return $sources;
  }

}
