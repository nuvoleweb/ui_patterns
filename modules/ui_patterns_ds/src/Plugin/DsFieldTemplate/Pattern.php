<?php

namespace Drupal\ui_patterns_ds\Plugin\DsFieldTemplate;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\ds\Plugin\DsFieldTemplate\DsFieldTemplateBase;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\Plugin\UiPatternsSourceManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\Plugin\UiPatternsSourceManager
   */
  protected $sourceManager;

  /**
   * The currently active request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

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
   *    UI Patterns manager.
   * @param \Drupal\ui_patterns\Plugin\UiPatternsSourceManager $source_manager
   *     UI Patterns source manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager, RequestStack $current_request) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternsManager = $patterns_manager;
    $this->sourceManager = $source_manager;
    $this->currentRequest = $current_request->getCurrentRequest();
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
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function alterForm(&$form) {
    $configuration = $this->getConfiguration();
    $this->buildPatternDisplayForm($form, 'ds_field_template', $this->getContext(), $configuration);
  }

  /**
   * Get source field plugin context.
   *
   * @return array
   *    Context array.
   */
  protected function getContext() {
    $request = $this->currentRequest->request;
    $fields = $request->get('fields');
    $trigger_element = $request->get('_triggering_element_name');
    preg_match('/fields\[(.*)\]/U', $trigger_element, $match);
    $field_name = $match[1];

    return [
      'field_name' => $field_name,
      'field_settings' => $fields[$field_name],
      'entity_type' => $request->get('ds_entity_type'),
      'bundle' => $request->get('ds_bundle'),
      'view_mode' => $request->get('ds_view_mode'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'pattern' => '',
      'pattern_mapping' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function massageRenderValues(&$field_settings, $values) {

  }

}
