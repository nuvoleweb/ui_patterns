<?php

namespace Drupal\Tests\ui_patterns\Unit;

use Drupal\Tests\UnitTestCase;

/**
 * Class AbstractUiPatternsTest.
 *
 * @package Drupal\Tests\ui_patterns\Unit
 */
abstract class AbstractUiPatternsTest extends UnitTestCase {

  /**
   * Get fixtures base path.
   *
   * @return string
   *    Fixtures base path.
   */
  protected function getFixturePath() {
    return realpath(dirname(__FILE__) . '/../fixtures');
  }

}
