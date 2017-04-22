<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Class AbstractUiPatternsTest.
 *
 * @package Drupal\Tests\ui_patterns\Kernel
 */
abstract class AbstractUiPatternsTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'ui_patterns',
    'ui_patterns_test',
  ];

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
