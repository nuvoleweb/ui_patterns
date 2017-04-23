<?php

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ui_patterns\Plugin\PatternSourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "fields",
 *   label = @Translation("Fields"),
 *   provider = "field",
 *   tags = {
 *     "entity_display"
 *   }
 * )
 */
class FieldSource extends PatternSourceBase implements ContainerFactoryPluginInterface {

  /**
   * Entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManager $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFields() {
    $sources = [];
    $fields = $this->entityManager->getFieldDefinitions($this->getContextProperty('entity_type'), $this->getContextProperty('entity_bundle'));

    /** @var \Drupal\Core\Field\FieldDefinitionInterface $field */
    foreach ($fields as $field) {
      if (!$this->getContextProperty('limit')) {
        $sources[] = $this->getSourceField($field->getName(), $field->getLabel());
      }
      elseif (in_array($field->getName(), $this->getContextProperty('limit'))) {
        $sources[] = $this->getSourceField($field->getName(), $field->getLabel());
      }
    }
    return $sources;
  }

}
