<?php

namespace Drupal\ui_patterns_field_group\Plugin\field_group\FieldGroupFormatter;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\field_group\FieldGroupFormatterBase;
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
class PatternFormatter extends FieldGroupFormatterBase implements ConfigurableFieldGroupFormatterInterface, ContainerFactoryPluginInterface {

  /**
   * The available pattern definitions.
   *
   * @var array
   */
  protected $patterns;

  /**
   * The field manager so that we can get the field names from the keys.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $fieldManager;

  /**
   * Constructs a Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct($configuration, $plugin_id, $plugin_definition, UiPatternsManager $patternsManager, EntityFieldManagerInterface $fieldManager) {
    parent::__construct($plugin_id, $plugin_definition, $configuration['group'], $configuration['settings'], $configuration['label']);
    $this->configuration = $configuration;
    $this->patterns = $patternsManager->getDefinitions();
    $this->fieldManager = $fieldManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    self::transformFormToSettings($configuration['settings']);
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ui_patterns'),
      $container->get('entity_field.manager')
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
   * {@inheritdoc}
   */
  public function settingsForm() {
    $form = parent::settingsForm();
    unset($form['id']);
    unset($form['classes']);

    $options = array_map(function ($pattern) {
      return $pattern['label'];
    }, $this->patterns);

    $form['pattern'] = [
      '#type' => 'select',
      '#title' => $this->t('Pattern'),
      '#options' => $options,
      '#default_value' => $this->getSetting('pattern'),
      '#weight' => -1,
    ];

    $field_options = [];
    if (isset($this->group->children)) {
      // Fields can come from Field API or contrib modules like Display Suite.
      // @todo: Generalize destination handling so that we cover all cases.
      // @link https://github.com/nuvoleweb/ui_patterns/issues/5
      $field_options = array_combine($this->group->children, $this->group->children);
    }

    $field_options = ['' => $this->t('- None -')] + $field_options;

    // Get the path of the pattern field.
    $pattern_path = ':input[name="format_settings[pattern]"]';
    if (isset($this->group->group_name)) {
      $pattern_path = ':input[name="fields[' . $this->group->group_name . '][settings_edit_form][settings][pattern]"]';
    }

    $pattern_map = $this->getSetting('pattern_map');
    foreach ($this->patterns as $key => $pattern) {
      $form['pattern__' . $key] = [
        '#type' => 'fieldset',
        '#title' => $pattern['label'],
        '#weight' => -1,
        '#states' => [
          'visible' => [
            $pattern_path => array('value' => $key),
          ],
        ],
      ];

      // The transitional settings are used when the display is not saved yet.
      $transitional_setting = $this->getSetting('pattern__' . $key);

      foreach ($pattern['fields'] as $name => $definition) {
        $default_value = '';

        if (isset($transitional_setting[$name])) {
          // When opening the form again, preserve the transitional value.
          $default_value = $transitional_setting[$name];
        }
        elseif (isset($pattern_map[$name])) {
          $default_value = $pattern_map[$name];
        }

        $form['pattern__' . $key][$name] = [
          '#type' => 'select',
          '#title' => $definition['label'],
          '#options' => $field_options,
          '#default_value' => $default_value,
        ];
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function transformFormToSettings(&$settings) {
    if (!isset($settings['pattern_map']) || !is_array($settings['pattern_map'])) {
      $settings['pattern_map'] = [];
      if (isset($settings['pattern__' . $settings['pattern']])) {
        $settings['pattern_map'] = $settings['pattern__' . $settings['pattern']];
      }

      // Filter out all the form elements that are not wanted.
      $settings = array_filter($settings, function ($key) {
        return strpos($key, 'pattern__') !== 0;
      }, ARRAY_FILTER_USE_KEY);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $label = 'None';
    if (isset($this->patterns[$this->getSetting('pattern')])) {
      $label = $this->patterns[$this->getSetting('pattern')]['label'];
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
      'pattern_map' => [],
    ) + parent::defaultContextSettings($context);
  }

}
