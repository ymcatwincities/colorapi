<?php

namespace Drupal\colorapi\TypedData\Definition;

/**
 * Interface for Typed Data API Color Complex Data definition.
 */
interface ColorDefinitionInterface {

  /**
   * Returns the hexadecimal value of the color.
   *
   * @return string
   *   The color in hexadecimal format (#XXXXXX where X is a hexadecimal
   *   character).
   */
  public function getHexadecimal();

  /**
   * Returns the Typed Data RBG color.
   *
   * @return \Drupal\colorapi\Plugin\DataType\RgbColorDataInterface
   *   The TypedData API RGB Color object for the color.
   */
  public function getRgb();

  /**
   * Returns the 'red' value of the color.
   *
   * @return int
   *   The red value of the color, a value between 0 and 255.
   */
  public function getRed();

  /**
   * Returns the 'green' value of the color.
   *
   * @return int
   *   The red value of the color, a value between 0 and 255.
   */
  public function getGreen();

  /**
   * Returns the 'blue' value of the color.
   *
   * @return int
   *   The red value of the color, a value between 0 and 255.
   */
  public function getBlue();

}
