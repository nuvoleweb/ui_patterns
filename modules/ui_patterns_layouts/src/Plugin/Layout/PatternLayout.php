<?php

namespace Drupal\ui_patterns_layouts\Plugin\Layout;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Layout\LayoutDefinition;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\ui_patterns\UiPatternsLegacyManager;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LayoutDefault.
 *
 * @package Drupal\ui_patterns_layouts\Plugin\Layout
 */
class PatternLayout extends LayoutDefault implements PluginFormInterface, ContainerFactoryPluginInterface {

  /**
   * Module Handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler = NULL;

  /**
   * The element info.
   *
   * @var \Drupal\Core\Render\ElementInfoManagerInterface
   */
  protected $elementInfo;

  /**
   * Constructs a LocalActionDefault object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param \Drupal\Core\Layout\LayoutDefinition $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Render\ElementInfoManagerInterface $element_info
   *   Element info object.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler.
   */
  public function __construct(array $configuration, $plugin_id, LayoutDefinition $plugin_definition, ElementInfoManagerInterface $element_info, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->elementInfo = $element_info;
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
      $container->get('plugin.manager.element_info'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $configuration = $this->getConfiguration();

    // Components expect slots to be passed along in a render array fashion.
    $slots = [];
    foreach (array_keys($regions) as $region_name) {
      $slots[$region_name] = $regions[$region_name];
    }

    return [
      '#type' => 'component',
      '#component' => $this->getPluginDefinition()->id(),
      '#slots' => $slots,
      '#variant' => $configuration['pattern']['variant'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'pattern' => [
        'field_templates' => 'default',
        'variant' => '',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $form['pattern'] = [
      '#group' => 'additional_settings',
      '#type' => 'details',
      '#title' => $this->t('Pattern settings'),
      '#tree' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration = $form_state->getValues();
  }

}
