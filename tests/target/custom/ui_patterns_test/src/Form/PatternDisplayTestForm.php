<?php

namespace Drupal\ui_patterns_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ui_patterns\Form\PatternDisplayForm;
use Drupal\ui_patterns\Plugin\UiPatternsSourceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * Class PatternDisplayForm.
 *
 * @package Drupal\ui_patterns\Form
 */
class PatternDisplayTestForm extends PatternDisplayForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $form['actions'] = [
      '#tree' => FALSE,
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save mapping'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
