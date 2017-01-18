<?php

namespace Drupal\ui_patterns_field_group\Plugin\field_group\FieldGroupFormatter;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\field_group\FieldGroupFormatterBase;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\Plugin\UiPatternsSourceManager;
use Drupal\ui_patterns\UiPatternsManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'paragraph' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "pattern_formatter",
 *   label = @Translation("Pattern"),
 *   description = @Translation("Wrap fields as a pattern."),
 *   supported_contexts = {
 *     "view",
 *   }
 * )
 */
class PatternFormatter extends FieldGroupFormatterBase implements ContainerFactoryPluginInterface {

  use PatternDisplayFormTrait;

  /**
   * The available pattern definitions.
   *
   * @var array
   */
  protected $patternsManager;

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $sourceManager;

  /**
   * Constructs a Drupal\Component\Plugin\PluginBase object.
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
  public function __construct($configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager) {
    parent::__construct($plugin_id, $plugin_definition, $configuration['group'], $configuration['settings'], $configuration['label']);
    $this->configuration = $configuration;
    $this->patternsManager = $patterns_manager;
    $this->sourceManager = $source_manager;
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
      $container->get('plugin.manager.ui_patterns_source')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function preRender(&$element, $rendering_object) {
    $element['#theme'] = 'pattern__' . $this->getSetting('pattern') . '__' . $this->group->group_name;

    $mapping = $this->getSetting('pattern_map');
    foreach ($mapping as $key => $field) {
      // Make sure none of the keys are called 'type' or drupal will freak out.
      if (isset($element[$field]) && $key != 'type') {
        $element['#' . $key] = $element[$field];
      }
    }

  }

  /**
   * Get field group name.
   *
   * @return string
   *    Field group name.
   */
  protected function getFieldGroupName() {
    return $this->configuration['group']->group_name;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm() {
    $form = parent::settingsForm();
    unset($form['id']);
    unset($form['classes']);

    $this->buildPatternDisplayForm($form, 'test', ['field_group' => $this], $this->configuration);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $label = 'None';
    $definitions = $this->patternsManager->getDefinitions();
    if (isset($definitions[$this->getSetting('pattern')])) {
      $label = $definitions[$this->getSetting('pattern')]['label'];
    }

    $summary = [
      $this->t('Pattern: @pattern', ['@pattern' => $label]),
    ];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return array(
      'pattern' => 'none',
      'pattern_mapping' => [],
    ) + parent::defaultContextSettings($context);
  }

}
