<?php

namespace Drupal\ui_patterns_field_group\Plugin\field_group\FieldGroupFormatter;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\field_group\FieldGroupFormatterBase;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\UiPatternsSourceManager;
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
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager) {
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
    $mapping = $this->getSetting('pattern_mapping');
    foreach ($mapping as $field) {
      $this->buildFieldGroupElements($element, $field);
      $element['#fields'][$field['destination']][] = $element[$field['source']];
    }
    $this->determineConfigSettings($element, $this->getSetting('pattern'));
  }

  /**
   * Recursive method building the fieldgroup content.
   *
   * This method checks if one of the fieldgroup items are a fieldgroup pattern
   * themselve. If so, we must build its configuration again and check if this
   * fieldgroup doesn't have fieldgroup pattern items itself. (And the story
   * keeps going until there are no more people alive on earth).
   *
   * @param array $element
   *   Renderable array of the outputed content.
   * @param array $field
   *   Pattern config settings.
   */
  protected function buildFieldGroupElements(array &$element, array $field) {
    if ($field['plugin'] == 'fieldgroup') {
      $group_settings = $this->getSubFieldgroupPatternSettings($field);

      // Build pattern group children content.
      foreach ($group_settings["format_settings"]["pattern_mapping"] as $child) {
        if ($child['plugin'] == 'fieldgroup') {
          $this->buildFieldGroupElements($element[$field['source']], $child);
        }
        $element[$field['source']]['#fields'][$child['destination']][] = $element[$field['source']][$child['source']];
      }
      $this->determineConfigSettings($element[$field['source']], $group_settings['format_settings']['pattern']);
    }
    $element[$field['destination']][] = $element[$field['source']];
  }

  /**
   * Helper to get the pattern subfieldgroup settings.
   *
   * @param array $field
   *   Array if fieldgroup pattern fields config. Its determines the type of
   *   each field within the pattern, its source and its destination.
   *
   * @return array
   *   Array of settings for the group.
   */
  protected function getSubFieldgroupPatternSettings(array $field) {
    $config_name_pieces = [];

    // Build the key name of the view display config that we will retrieve
    // the group config from.
    foreach (['entity_type', 'bundle', 'mode'] as $key) {
      $config_name_pieces[] = $this->configuration["group"]->{$key};
    }
    $config_name = implode('.', $config_name_pieces);

    // @TODO:
    // - Figure out if temporary modifications in the other fieldgroup
    // patterns can be fetch. When loading from config storage, we may not
    // have the latest changes.
    // Fetch the child pattern configuration to know which field goes where.
    $storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
    $view_display = $storage->load($config_name);
    $group_settings = $view_display->getThirdPartySetting('field_group', $field['source']);

    return $group_settings;
  }

  /**
   * Helper to build the context expected to render the fieldgroup pattern.
   *
   * @param array $element
   *   Field data.
   * @param string $pattern_id
   *   Machine name of the pattern to load.
   */
  protected function determineConfigSettings(array &$element, $pattern_id) {
    $element['#id'] = $pattern_id;

    $element['#type'] = 'pattern';
    $element['#multiple_sources'] = TRUE;

    // Allow default context values to not override those exposed elsewhere.
    $element['#context']['type'] = 'field_group';
    $element['#context']['group_name'] = $this->configuration['group']->group_name;
    $element['#context']['entity_type'] = $this->configuration['group']->entity_type;
    $element['#context']['bundle'] = $this->configuration['group']->bundle;
    $element['#context']['view_mode'] = $this->configuration['group']->mode;

    // Pass current entity to pattern context, if any.
    $element['#context']['entity'] = $this->findEntity($element['#fields']);
  }

  /**
   * Look for entity object in fields array.
   *
   * @param array $fields
   *   Fields array.
   *
   * @return \Drupal\Core\Entity\ContentEntityBase|null
   *   Entity object or NULL if none found.
   */
  protected function findEntity(array $fields) {
    foreach ($fields as $field) {
      if (isset($field['#object']) && is_object($field['#object']) && $field['#object'] instanceof ContentEntityBase) {
        return $field['#object'];
      }
      if (is_array($field)) {
        return $this->findEntity($field);
      }
    }
    return NULL;
  }

  /**
   * Get field group name.
   *
   * @return string
   *   Field group name.
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
    ] + parent::defaultContextSettings($context);
  }

}
