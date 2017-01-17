<?php

namespace Drupal\ui_patterns\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\Plugin\UiPatternsSourceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * Class PatternDisplayForm.
 *
 * @package Drupal\ui_patterns\Form
 */
abstract class PatternDisplayForm extends FormBase {

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $sourceManager;

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\Plugin\UiPatternsSourceManager
   */
  protected $patternsManager;

  /**
   * PatternDisplayForm constructor.
   *
   * @param \Drupal\ui_patterns\UiPatternsManager $patterns_manager
   *    UI Patterns manager.
   */
  public function __construct(UiPatternsManager $patterns_manager, UiPatternsSourceManager $source_manager) {
    $this->patternsManager = $patterns_manager;
    $this->sourceManager = $source_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.ui_patterns'),
      $container->get('plugin.manager.ui_patterns_source')
    );
  }

  /**
   * Get field source plugin tags.
   *
   * @return array
   *    Field definitions.
   */
  abstract public function getTags();

  /**
   * Get field definitions for given context.
   *
   * @return SourceField[]
   *    Field definitions.
   */
  public function getFieldDefinitions() {
    $fields = [];
    foreach ($this->getTags() as $tag) {
      $fields += $this->sourceManager->getFieldsByTag($tag);
    }

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pattern_display_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['pattern'] = [
      '#type' => 'select',
      '#empty_value' => '_none',
      '#title' => $this->t('Pattern'),
      '#options' => $this->patternsManager->getPatternsOptions(),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxRebuildForm',
        'wrapper' => 'pattern-mapping-wrapper',
        'progress' => array(
          'type' => 'throbber',
          'message' => $this->t('Loading pattern...'),
        ),
      ],
    ];

    $pattern = $form_state->getValue('pattern');
    if ($pattern) {
      $form['mapping'] = $this->getMappingForm($pattern);
    }

    $form['actions'] = [
      '#tree' => FALSE,
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save mapping'),
      '#button_type' => 'primary',
    );
    $form['#prefix'] = '<div id="pattern-mapping-wrapper">';
    $form['#suffix'] = '</div>';
    return $form;
  }

  /**
   * Get mapping form.
   *
   * @param string $pattern_id
   *    Pattern ID for which to print the mapping form for.
   *
   * @return array
   *    Mapping form.
   */
  public function getMappingForm($pattern_id) {

    $elements = [
      '#type' => 'table',
      '#header' => [
        $this->t('Source'),
        $this->t('Plugin'),
        $this->t('Destination'),
        $this->t('Weight'),
      ],
    ];
    $elements['#tabledrag'][] = [
      'action' => 'order',
      'relationship' => 'sibling',
      'group' => 'field-weight',
    ];

    $destinations = ['_hidden' => $this->t('- Hidden -')] + $this->patternsManager->getPatternFieldsOptions($pattern_id);

    foreach ($this->getFieldDefinitions() as $field_name => $field) {
      $elements[$field_name] = [
        'info' => [
          '#plain_text' => $field->getFieldLabel(),
        ],
        'plugin' => [
          '#plain_text' => $field->getPluginLabel(),
        ],
        'destination' => [
          '#type' => 'select',
          '#title' => $this->t('Destination for @field', ['@field' => $field->getFieldLabel()]),
          '#title_display' => 'invisible',
          '#default_value' => '_disabled',
          '#options' => $destinations,
        ],
        'weight' => [
          '#type' => 'weight',
          '#default_value' => 0,
          '#delta' => 20,
          '#title' => $this->t('Weight for @field field', array('@field' => $field->getFieldLabel())),
          '#title_display' => 'invisible',
          '#attributes' => [
            'class' => ['field-weight'],
          ],
        ],
        '#attributes' => [
          'class' => ['draggable'],
        ],
      ];
    }

    return $elements;
  }

  /**
   * Ajax submit handler that will return the whole form structure.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function ajaxRebuildForm(array &$form, FormStateInterface $form_state) {
    return $form;
  }

}
