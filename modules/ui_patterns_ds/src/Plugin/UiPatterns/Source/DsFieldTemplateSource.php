<?php

namespace Drupal\ui_patterns_ds\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\Core\TypedData\TypedDataManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityFieldManager;

/**
 * Defines Display Suite field template source plugin.
 *
 * @UiPatternsSource(
 *   id = "ds_field_template",
 *   label = @Translation("Field template"),
 *   provider = "ds",
 *   tags = {
 *     "ds_field_template"
 *   }
 * )
 */
class DsFieldTemplateSource extends UiPatternsSourceBase {

  /**
   * Entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $fieldManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $ui_patterns_manager, TypedDataManager $typed_data_manager, EntityFieldManager $field_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $ui_patterns_manager, $typed_data_manager);
    $this->fieldManager = $field_manager;
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
    $field_name = $this->getContextProperty('field_name');
    $entity_type = $this->getContextProperty('entity_type');
    $bundle = $this->getContextProperty('bundle');

    /** @var \Drupal\field\Entity\FieldConfig $field */
    $field = $this->fieldManager->getFieldDefinitions($entity_type, $bundle)[$field_name];
    $label = $field->label();

    $sources[] = $this->getSourceField($field_name, $label);
    foreach ($field->getFieldStorageDefinition()->getColumns() as $column_name => $column) {
      $sources[] = $this->getSourceField($field_name . '__' . $column_name, $label . ': ' . $column_name);
    }
    return $sources;
  }

}
