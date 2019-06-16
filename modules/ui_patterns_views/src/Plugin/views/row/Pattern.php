<?php

namespace Drupal\ui_patterns_views\Plugin\views\row;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\UiPatternsSourceManager;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Plugin\views\row\Fields;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Pattern Views row plugin.
 *
 * @ingroup views_row_plugins
 *
 * @ViewsRow(
 *   id = "ui_patterns",
 *   title = @Translation("Pattern"),
 *   help = @Translation("Displays fields using a pattern."),
 *   theme = "pattern_views_row",
 *   display_types = {"normal"}
 * )
 */
class Pattern extends Fields {

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
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternsManager = $patterns_manager;
    $this->sourceManager = $source_manager;
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
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['hide_empty'] = ['default' => FALSE];
    $options['default_field_elements'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['default_field_elements'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Provide default field wrapper elements'),
      '#default_value' => $this->options['default_field_elements'],
      '#description' => $this->t('If not checked, fields that are not configured to customize their HTML elements will get no wrappers at all for their field, label and field + label wrappers. You can use this to quickly reduce the amount of markup the view provides by default, at the cost of making it more difficult to apply CSS.'),
    ];

    $form['hide_empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide empty fields'),
      '#default_value' => $this->options['hide_empty'],
      '#description' => $this->t('Do not display fields, labels or markup for fields that are empty.'),
    ];

    $context = ['view' => $this->view];
    $this->buildPatternDisplayForm($form, 'views_row', $context, $this->options);
  }

  /**
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    $settings = $form_state->getValue('row_options');
    self::processFormStateValues($settings);
    $form_state->setValue('row_options', $settings);
  }

  /**
   * Helper function: check for all conditions that make a field visible.
   *
   * @param \Drupal\views\Plugin\views\field\FieldPluginBase $field
   *   Field object.
   * @param \Drupal\Component\Render\MarkupInterface|null $field_output
   *   Field output.
   *
   * @return bool
   *   TRUE if a field should be visible, FALSE otherwise.
   *
   * @see template_preprocess_pattern_views_row()
   */
  public function isFieldVisible(FieldPluginBase $field, $field_output) {
    $empty_value = $field->isValueEmpty($field_output, $field->options['empty_zero']);
    $hide_field = !$empty_value || (empty($field->options['hide_empty']) && empty($this->options['hide_empty']));
    $empty = empty($field->options['exclude']) && $hide_field;
    return $empty && $this->hasMappingDestination('views_row', $field->field, $this->options);
  }

}
