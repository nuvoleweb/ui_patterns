<?php

namespace Drupal\ui_patterns\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractUiPatternsTest.
 *
 * @package Drupal\ui_patterns\Tests\Unit
 */
abstract class AbstractUiPatternsTest extends TestCase {

  /**
   * Get full test extension path.
   *
   * @param string $name
   *    Test extension name.
   *
   * @return string
   *    Full test extension path.
   */
  protected function getExtensionsPath($name) {
    return realpath(dirname(__FILE__) . '/../../../tests/target/custom/' . $name);
  }

}
