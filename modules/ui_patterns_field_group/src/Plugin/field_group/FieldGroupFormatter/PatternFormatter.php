<?php

namespace Drupal\ui_patterns_field_group\Plugin\field_group\FieldGroupFormatter;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\field_group\FieldGroupFormatterBase;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\UiPatternsSourceManager;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\ui_patterns_field_group\Utility\EntityFinder;
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
   * Entity finder utility.
   *
   * @var \Drupal\ui_patterns_field_group\Utility\EntityFinder
   */
  protected $entityFinder;

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
   *   UI Patterns manager.
   * @param \Drupal\ui_patterns\UiPatternsSourceManager $source_manager
   *   UI Patterns source manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($plugin_id, $plugin_definition, $configuration['group'], $configuration['settings'], $configuration['label']);
    $this->configuration = $configuration;
    $this->patternsManager = $patterns_manager;
    $this->sourceManager = $source_manager;
    $this->entityFinder = new EntityFinder();
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
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function preRender(&$element, $rendering_object) {
    $this->preRenderGroup($element, $this->group->group_name, $rendering_object);
  }

  /**
   * Recursive method to build the fieldgroup content.
   *
   * This method checks if one of the fieldgroup items are a fieldgroup pattern
   * themselve. If so, we must build its configuration again and check if this
   * fieldgroup doesn't have fieldgroup pattern items itself. (And the story
   * keeps going until there are no more people alive on earth).
   *
   * @param array $element
   *   Renderable array of the outputed content.
   * @param array $group_settings
   *   Pattern config settings.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function preRenderGroup(array &$element, $group_name, array $rendering_object) {
    // Do not pre render the group twice.
    if (!empty($element['#pattern_pre_rendered'])) {
      return;
    }

    // Load field group settings.
    $group = $rendering_object['#fieldgroups'][$group_name];

    // Handle groups managed by UI Patterns recursively.
    if ($group->format_type == 'pattern_formatter') {
      // Move content into their fields.
      foreach ($group->format_settings['pattern_mapping'] as $field) {
        if ($field['plugin'] == 'fieldgroup') {
          if ($field['source'] === '_label') {
            $element[$field['source']] = ['#markup' => $group->label];
          }
          else {
            $this->preRenderGroup($element[$field['source']], $field['source'], $rendering_object);
          }
        }
        $element['#fields'][$field['destination']][$field['source']] = $element[$field['source']];
      }

      // Add render array metadata.
      $this->addRenderContext($element, $group->format_settings);
    }
    // Fallback to default pre_rendering for fieldgroups not managed by UI
    // Patterns.
    else {
      field_group_pre_render($element, $group, $rendering_object);
    }
  }

  /**
   * Helper to build the context expected to render the fieldgroup pattern.
   *
   * @param array $element
   *   Field data.
   * @param array $format_settings
   *   The pattern format settings.
   */
  protected function addRenderContext(array &$element, array $format_settings) {
    $element['#id'] = $format_settings['pattern'];
    if (!empty($format_settings['pattern_variant'])) {
      $element['#variant'] = $format_settings['pattern_variant'];
    }

    $element['#type'] = 'pattern';
    $element['#multiple_sources'] = TRUE;

    // Allow default context values to not override those exposed elsewhere.
    $element['#context']['type'] = 'field_group';
    $element['#context']['group_name'] = $this->configuration['group']->group_name;
    $element['#context']['entity_type'] = $this->configuration['group']->entity_type;
    $element['#context']['bundle'] = $this->configuration['group']->bundle;
    $element['#context']['view_mode'] = $this->configuration['group']->mode;

    // Pass current entity to pattern context, if any.
    if (!empty($element['#fields'])) {
      $element['#context']['entity'] = $this->entityFinder->findEntityFromFields($element['#fields']);
    }

    // Nested groups can be rendered in any order so mark this one as done to
    // prevent issues.
    $element['#pattern_pre_rendered'] = TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm() {
    $form = parent::settingsForm();
    unset($form['id']);
    unset($form['classes']);

    if (isset($this->configuration['group']->children) && !empty($this->configuration['group']->children)) {
      $context = [
        'entity_type' => $this->configuration['group']->entity_type,
        'entity_bundle' => $this->configuration['group']->bundle,
        'entity_view_mode' => $this->configuration['group']->mode,
        'limit' => $this->configuration['group']->children,
      ];

      $this->buildPatternDisplayForm($form, 'entity_display', $context, $this->configuration['settings']);
    }
    else {
      $form['message'] = [
        '#markup' => $this->t('<b>Attention:</b> you have to add fields to this field group and save the whole entity display before being able to to access the pattern display configuration.'),
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $label = $this->t('None');
    if (!empty($this->getSetting('pattern'))) {
      $label = $this->patternsManager->getDefinition($this->getSetting('pattern'))->getLabel();
    }

    return [
      $this->t('Pattern: @pattern', ['@pattern' => $label]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return [
      'pattern' => '',
      'pattern_mapping' => [],
      'pattern_variant' => '',
    ] + parent::defaultContextSettings($context);
  }

}
