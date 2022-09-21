<?php

namespace Drupal\ui_patterns_ds;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Field template processor for Display Suite integration.
 *
 * @package Drupal\ui_patterns_ds
 */
class FieldTemplateProcessor implements FieldTemplateProcessorInterface {

  /**
   * Variables array.
   *
   * @var array
   */
  protected $variables = [];

  /**
   * {@inheritdoc}
   */
  public function process(array &$variables) {
    $this->variables = $variables;

    $content = [];
    foreach (array_keys($variables['items']) as $delta) {
      $fields = [];
      foreach ($this->getMapping() as $mapping) {
        $fields[$mapping['destination']][] = $this->getSourceValue($mapping, $delta);
      }

      $content['pattern_' . $delta] = [
        '#type' => 'pattern',
        '#id' => $this->getPatternId(),
        '#variant' => $this->getVariant(),
        '#fields' => $fields,
        '#context' => $this->getContext(),
        '#multiple_sources' => TRUE,
      ];
    }

    $variables['pattern'] = $content;
  }

  /**
   * Get source value.
   *
   * @param array $mapping
   *   Mapping array.
   * @param int $delta
   *   Field delta.
   *
   * @return mixed
   *   Source value.
   */
  public function getSourceValue(array $mapping, $delta) {
    $value = $this->variables['items'][$delta]['content'];
    if ($mapping['source'] != $this->getFieldName()) {
      $column = $this->getColumnName($mapping['source']);
      $value = $this->getEntity()->get($this->getFieldName())->getValue();
      $value = $value[$delta][$column];
    }
    return $value;
  }

  /**
   * Get field parent entity.
   *
   * @return \Drupal\Core\Entity\ContentEntityBase
   *   Parent entity.
   */
  protected function getEntity() {
    return $this->variables['element']['#object'];
  }

  /**
   * Get Pattern ID.
   *
   * @return string
   *   Pattern ID.
   */
  protected function getPatternId() {
    return $this->getSetting('pattern');
  }

  /**
   * Get mapping settings.
   *
   * @return array
   *   Mapping settings.
   */
  protected function getMapping() {
    return $this->getSetting('pattern_mapping', []);
  }

  /**
   * Get mapping settings.
   *
   * @return string
   *   Mapping settings.
   */
  protected function getVariant() {
    return $this->getSetting('pattern_variant');
  }

  /**
   * Get setting value or default to given value if none set.
   *
   * @param string $name
   *   Setting name.
   * @param string $default
   *   Setting default value.
   *
   * @return mixed
   *   Setting value.
   */
  protected function getSetting($name, $default = '') {
    return $this->variables['ds-config']['settings'][$name] ?? $default;
  }

  /**
   * Get field name.
   *
   * @return string
   *   Field name.
   */
  protected function getFieldName() {
    return $this->variables['field_name'];
  }

  /**
   * Extract column name from a source name.
   *
   * @param string $source
   *   Source name.
   *
   * @return string
   *   Column name.
   */
  protected function getColumnName($source) {
    return str_replace($this->getFieldName() . '__', '', $source);
  }

  /**
   * Get pattern context.
   *
   * @return array
   *   Pattern context.
   */
  protected function getContext() {
    $element = $this->variables['element'];
    $context = [
      'type' => 'ds_field_template',
      'field_name' => $this->getFieldName(),
      'entity_type' => $element['#entity_type'],
      'bundle' => $element['#bundle'],
      'view_mode' => $element['#view_mode'],
      'entity' => NULL,
    ];

    if (isset($element['#object']) && is_object($element['#object']) && $element['#object'] instanceof ContentEntityBase) {
      $context['entity'] = $element['#object'];
    }

    return $context;
  }

}
