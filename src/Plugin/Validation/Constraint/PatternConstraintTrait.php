<?php

namespace Drupal\ui_patterns\Plugin\Validation\Constraint;

use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Class BasePatternConstraint.
 *
 * @package Drupal\ui_patterns\Plugin\Validation\Constraint
 */
trait PatternConstraintTrait {

  /**
   * Execution context object.
   *
   * @var \Symfony\Component\Validator\ExecutionContextInterface
   */
  protected $context;

  /**
   * {@inheritdoc}
   */
  public function initialize(ExecutionContextInterface $context) {
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function validatedBy() {
    return self::class;
  }

}
