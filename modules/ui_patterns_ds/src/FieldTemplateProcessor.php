<?php

namespace Drupal\ui_patterns_ds;

use Drupal\ui_patterns\UiPatternsManager;
use Drupal\Core\Entity\EntityFieldManager;

/**
 * Class FieldTemplateProcessor.
 *
 * @package Drupal\ui_patterns_ds
 */
class FieldTemplateProcessor implements FieldTemplateProcessorInterface {

  /**
   * Ui Patterns Manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * Entity Field Manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $fieldManager;

  /**
   * Variables array.
   *
   * @var array
   */
  protected $variables = [];

  /**
   * Constructor.
   *
   * @param \Drupal\ui_patterns\UiPatternsManager $patterns_manager
   *    UI Patterns manager.
   * @param \Drupal\Core\Entity\EntityFieldManager $field_manager
   *    Field manager.
   */
  public function __construct(UiPatternsManager $patterns_manager, EntityFieldManager $field_manager) {
    $this->patternsManager = $patterns_manager;
    $this->fieldManager = $field_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function process(&$variables) {
    $this->variables = $variables;

    $fields = [];
    foreach ($this->getMapping() as $mapping) {
      if ($mapping['source'] == $this->getFieldName()) {
        $fields[$mapping['destination']] = $variables['items'];
      }
    }

    $variables['pattern'] = [
      '#type' => 'pattern',
      '#id' => $this->getPatternId(),
      '#fields' => $fields,
    ];
  }

  /**
   * Get Pattern ID.
   *
   * @return string
   *    Pattern ID.
   */
  protected function getPatternId() {
    return $this->variables['ds-config']['settings']['pattern'];
  }

  /**
   * Get mapping settings.
   *
   * @return mixed
   *    Mapping settings.
   */
  protected function getMapping() {
    return $this->variables['ds-config']['settings']['pattern_mapping'];
  }

  /**
   * Get field name.
   *
   * @return string
   *    Field name.
   */
  protected function getFieldName() {
    return $this->variables['field_name'];
  }

  /**
   * Get field columns.
   *
   * @return string
   *    Field columns.
   */
  protected function getColumns() {
    /** @var \Drupal\field\Entity\FieldConfig[] $fields */
    $fields = $this->fieldManager->getFieldDefinitions($this->getEntityType(), $this->getBundle());
    return $fields[$this->getFieldName()]->getFieldStorageDefinition()->getColumns();
  }

  /**
   * Get entity bundle.
   *
   * @return string
   *    Entity bundle.
   */
  protected function getBundle() {
    return $this->variables['element']['#bundle'];
  }

  /**
   * Get entity type.
   *
   * @return string
   *    Entity type.
   */
  protected function getEntityType() {
    return $this->variables['entity_type'];
  }

}
