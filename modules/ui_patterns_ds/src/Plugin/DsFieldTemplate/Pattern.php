<?php

namespace Drupal\ui_patterns_ds\Plugin\DsFieldTemplate;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ds\Plugin\DsFieldTemplate\DsFieldTemplateBase;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\UiPatternsSourceManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Entity\EntityFieldManager;

/**
 * Plugin for the expert field template.
 *
 * @DsFieldTemplate(
 *   id = "pattern",
 *   title = @Translation("Pattern"),
 *   theme = "pattern_ds_field_template",
 * )
 */
class Pattern extends DsFieldTemplateBase implements ContainerFactoryPluginInterface {

  use PatternDisplayFormTrait;

  /**
   * Module Handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler = NULL;

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsSourceManager
   */
  protected $sourceManager;

  /**
   * Current $_POST parameters.
   *
   * @var \Symfony\Component\HttpFoundation\ParameterBag
   */
  protected $parameters;

  /**
   * Entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $fieldManager;

  /**
   * Pattern constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\ui_patterns\UiPatternsManager $patterns_manager
   *   UI Patterns manager.
   * @param \Drupal\ui_patterns\UiPatternsSourceManager $source_manager
   *   UI Patterns source manager.
   * @param \Symfony\Component\HttpFoundation\RequestStack $parameters
   *   Current $_POST parameters.
   * @param \Drupal\Core\Entity\EntityFieldManager $field_manager
   *   Field manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager, RequestStack $parameters, EntityFieldManager $field_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternsManager = $patterns_manager;
    $this->sourceManager = $source_manager;
    $this->parameters = $parameters->getCurrentRequest()->request;
    $this->fieldManager = $field_manager;
    $this->moduleHandler = $module_handler;
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
      $container->get('plugin.manager.ui_patterns_source'),
      $container->get('request_stack'),
      $container->get('entity_field.manager'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function alterForm(&$form) {
    $context = $this->getContext();
    if ($this->isSupportedField($context)) {
      $this->buildPatternDisplayForm($form, 'ds_field_template', $context, $this->getConfiguration());
    }
    else {
      $form['#markup'] = $this->t("The current field is not supported.");
    }
  }

  /**
   * Get source field plugin context.
   *
   * @return array
   *   Context array.
   */
  protected function getContext() {
    $fields = $this->parameters->get('fields');
    $field_name = $this->getCurrentField();

    return [
      'field_name' => $field_name,
      'field_settings' => $fields[$field_name],
      'entity_type' => $this->parameters->get('ds_entity_type'),
      'bundle' => $this->parameters->get('ds_bundle'),
      'view_mode' => $this->parameters->get('ds_view_mode'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'pattern' => '',
      'pattern_variant' => '',
      'pattern_mapping' => [],
    ];
  }

  /**
   * Get name of field currently being edited.
   *
   * @return string
   *   Name of field currently being edited.
   */
  protected function getCurrentField() {
    $fields = array_filter($this->parameters->get('fields', []), function ($field) {
      return isset($field['settings_edit_form']['third_party_settings']['ds']['ft']['id']) && $field['settings_edit_form']['third_party_settings']['ds']['ft']['id'] == 'pattern';
    });
    $fields = array_keys($fields);
    $field = reset($fields);

    if (empty($field)) {
      $trigger_element = $this->parameters->get('_triggering_element_name');
      $field = str_replace('_plugin_settings_edit', '', $trigger_element);
    }

    return $field;
  }

  /**
   * Pattern Display Suite field template plugin only supports actual fields.
   *
   * @param array $context
   *   Current context.
   *
   * @return bool
   *   TRUE if supported, FALSE otherwise.
   */
  protected function isSupportedField(array $context) {
    /** @var \Drupal\field\Entity\FieldConfig $field */
    if ($context['entity_type'] && $context['bundle']) {
      $field = $this->fieldManager->getFieldDefinitions($context['entity_type'], $context['bundle']);
      return isset($field[$context['field_name']]);
    }
    return FALSE;
  }

}
