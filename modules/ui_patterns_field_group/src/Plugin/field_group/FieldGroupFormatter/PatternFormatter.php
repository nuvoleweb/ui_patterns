<?php

namespace Drupal\ui_patterns_field_group\Plugin\field_group\FieldGroupFormatter;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Element;
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

    $fields = [];
    $mapping = $this->getSetting('pattern_mapping');
    foreach ($mapping as $field) {
      if ($field['plugin'] == 'fieldgroup') {
        $entity_type = $this->configuration["group"]->entity_type;
        $entity_bundle = $this->configuration["group"]->bundle;
        $entity_view_mode = $this->configuration["group"]->mode;

        // @TODO:
        // - Figure out if temporary modification in the other fieldgroup
        // patterns can be fetch. When loading from config storage, we may not
        // have the latest changes.
        // - Make this recursive. A fieldgroup can have a fieldgroup child that
        // can have a fieldgroup child and so on...

        // Fetch the child pattern configuration to know which field goes where.
        $storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
        $view_display = $storage->load("$entity_type.$entity_bundle.$entity_view_mode");
        $group_settings = $view_display->getThirdPartySetting('field_group', $field['source']);

        // Build pattern group children content.
        $child_fields = [];
        foreach ($group_settings["format_settings"]["pattern_mapping"] as $child) {
          $child_fields[$child['destination']][] = $element[$field['source']][$child['source']];
        }
        $element[$field['source']]['#fields'] = $child_fields;

        // Set config. This is identical to below and will need some
        // factorization.
        $element[$field['source']]['#type'] = 'pattern';
        $element[$field['source']]['#id'] = $group_settings['format_settings']['pattern'];
        $element[$field['source']]['#multiple_sources'] = TRUE;
        $element[$field['source']]['#context']['type'] = 'field_group';
        $element[$field['source']]['#context']['group_name'] = $field['source'];
        $element[$field['source']]['#context']['entity_type'] = $entity_type;
        $element[$field['source']]['#context']['bundle'] = $entity_bundle;
        $element[$field['source']]['#context']['view_mode'] = $entity_view_mode;

        // Pass current entity to pattern context, if any.
        $element[$field['source']]['#context']['entity'] = $this->findEntity($child_fields);
      }
      $fields[$field['destination']][] = $element[$field['source']];
    }

    $element['#type'] = 'pattern';
    $element['#id'] = $this->getSetting('pattern');
    $element['#fields'] = $fields;
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

