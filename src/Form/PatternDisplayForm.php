<?php

namespace Drupal\ui_patterns\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * Class PatternDisplayForm.
 *
 * @package Drupal\ui_patterns\Form
 */
class PatternDisplayForm extends FormBase {

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * PatternDisplayForm constructor.
   *
   * @param \Drupal\ui_patterns\UiPatternsManager $patterns_manager
   *    UI Patterns manager.
   */
  public function __construct(UiPatternsManager $patterns_manager) {
    $this->patternsManager = $patterns_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.ui_patterns')
    );
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
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

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
          '#plain_text' => $field['label'],
        ],
        'destination' => [
          '#type' => 'select',
          '#title' => $this->t('Destination for @field', ['@field' => $field['label']]),
          '#title_display' => 'invisible',
          '#default_value' => '_disabled',
          '#options' => $destinations,
        ],
        'weight' => [
          '#type' => 'weight',
          '#default_value' => $field['weight'],
          '#delta' => 20,
          '#title' => $this->t('Weight for @field field', array('@field' => $field['label'])),
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

  /**
   * Get field definitions for given context.
   *
   * @return array
   *    Field definitions.
   */
  public function getFieldDefinitions() {
    return [
      'field_1' => [
        'name' => 'field_1',
        'label' => 'Field 1',
        'weight' => 0,
      ],
      'field_2' => [
        'name' => 'field_2',
        'label' => 'Field 2',
        'weight' => 0,
      ],
      'field_3' => [
        'name' => 'field_3',
        'label' => 'Field 3',
        'weight' => 0,
      ],
    ];
  }

}
