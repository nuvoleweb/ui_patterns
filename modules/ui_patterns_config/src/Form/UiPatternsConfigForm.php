<?php

namespace Drupal\ui_patterns_config\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UiPatternsConfigForm.
 *
 * @package Drupal\ui_patterns_config\Form
 */
class UiPatternsConfigForm extends EntityForm {

  /**
   * UiPatternsConfigForm constructor.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $pattern = $this->entity;

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $pattern->label(),
      '#description' => $this->t("Label of the pattern."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $pattern->id(),
      '#machine_name' => array(
        'exists' => array($this, 'exist'),
      ),
      '#disabled' => !$pattern->isNew(),
    );

    $form['definition'] = array(
      '#title' => 'Definition',
      '#type' => 'textarea',
      '#default_value' => $pattern->definition(),
    );

    $form['template'] = array(
      '#title' => 'Template',
      '#type' => 'textarea',
      '#default_value' => $pattern->template(),
    );

    $form['stylesheet'] = array(
      '#title' => 'Stylesheet',
      '#type' => 'textarea',
      '#default_value' => $pattern->stylesheet(),
    );

    $form['javascript'] = array(
      '#title' => 'Javascript',
      '#type' => 'textarea',
      '#default_value' => $pattern->javascript(),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $pattern = $this->entity;
    $status = $pattern->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label pattern.', array(
        '%label' => $pattern->label(),
      )));
    }
    else {
      drupal_set_message($this->t('The %label pattern was not saved.', array(
        '%label' => $pattern->label(),
      )));
    }

    $form_state->setRedirect('entity.ui_patterns_config.collection');
  }

  /**
   * Helper function to check whether a Pattern entity exists.
   */
  public function exist($id) {
    $entity = $this->entityQuery->get('ui_patterns_config')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
