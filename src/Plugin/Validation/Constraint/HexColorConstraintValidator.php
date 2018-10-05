<?php

namespace Drupal\colorapi\Plugin\Validation\Constraint;

use Drupal\colorapi\Plugin\Datatype\HexColorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the hexadecimal_color constraint.
 */
class HexColorConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {

    if (is_array($items)) {
      foreach ($items as $item) {
        if (!$this->isHexColorString($item)) {
          // The value is not a valid hexadecimal color string, so a violation,
          // aka error, is applied.
          $this->context->addViolation($constraint->notValidHexadecimalColorString, ['%value' => (string) $item]);
        }
      }
    }
    else {
      if (!$this->isHexColorString($items)) {
        $this->context->addViolation($constraint->notValidHexadecimalColorString, ['%value' => (string) $items]);
      }
    }
  }

  /**
   * Check if a string is a valid hexadecimal color string.
   *
   * @param mixed $value
   *   The item to check as a hexadecimal color string.
   *
   * @return bool
   *   TRUE if the given value is a valid hexadecimal color string. FALSE if it
   *   is not.
   */
  private function isHexColorString($value) {
    if (is_string($value)) {
      return preg_match(HexColorInterface::HEXADECIMAL_COLOR_REGEX, $value);
    }

    return FALSE;
  }

}
