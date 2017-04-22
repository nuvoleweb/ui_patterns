<?php

namespace Drupal\ui_patterns\Plugin\Validation\Constraint;

use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Constraint;

/**
 * ValidFieldNameConstraint constraint.
 *
 * @Constraint(
 *   id = "ValidPatternMachineName",
 *   label = @Translation("Valid pattern field name", context = "Validation")
 * )
 */
class ValidPatternMachineNameConstraint extends Constraint implements ConstraintValidatorInterface {

  use PatternConstraintTrait;

  /**
   * Error message.
   *
   * @var string
   */
  public $message = 'Field cannot be named "@field" since it is among pattern reserved words: @reserved.';

  /**
   * Reserved words.
   *
   * @var array
   */
  protected $reserved = ['id', 'type', 'theme', 'context', 'use', 'attributes'];

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if (in_array($value, $this->reserved)) {
      $this->context->addViolation($this->message, [
        '@field' => $value,
        '@reserved' => implode(', ', $this->reserved),
      ]);
    }
  }

}
