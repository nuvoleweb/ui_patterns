<?php

namespace Drupal\Tests\ui_patterns\Unit;

use Drupal\Tests\UnitTestCase;

/**
 * Abstract base test class.
 *
 * @group ui_patterns
 *
 * @package Drupal\Tests\ui_patterns\Unit
 */
abstract class AbstractUiPatternsTest extends UnitTestCase {

  /**
   * Get fixtures base path.
   *
   * @return string
   *   Fixtures base path.
   */
  protected function getFixturePath() {
    return realpath(dirname(__FILE__) . '/../fixtures');
  }

}
