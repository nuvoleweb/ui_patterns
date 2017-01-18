<?php

namespace Drupal\ui_patterns_views\Plugin\views\row;

use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\Form\PatternDisplayFormTrait;
use Drupal\ui_patterns\Plugin\UiPatternsSourceManager;
use Drupal\ui_patterns\UiPatternsManager;
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
 *   theme = "views_view_fields",
 *   display_types = {"normal"}
 * )
 */
class Pattern extends Fields {

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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
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
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['hide_empty'] = array('default' => FALSE);
    $options['default_field_elements'] = array('default' => TRUE);
    return $options;
  }

  /**
   * Provide a form for setting options.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $options = $this->displayHandler->getFieldLabels();

    if (empty($this->options['inline'])) {
      $this->options['inline'] = array();
    }

    $form['default_field_elements'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Provide default field wrapper elements'),
      '#default_value' => $this->options['default_field_elements'],
      '#description' => $this->t('If not checked, fields that are not configured to customize their HTML elements will get no wrappers at all for their field, label and field + label wrappers. You can use this to quickly reduce the amount of markup the view provides by default, at the cost of making it more difficult to apply CSS.'),
    );

    $form['hide_empty'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Hide empty fields'),
      '#default_value' => $this->options['hide_empty'],
      '#description' => $this->t('Do not display fields, labels or markup for fields that are empty.'),
    );

    $context = ['view' => $this->view];
    $this->buildPatternDisplayForm($form, 'views_row', $context, []);
  }

}
