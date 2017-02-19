<?php

namespace Drupal\ui_patterns_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class TestController.
 *
 * @package Drupal\ui_patterns_test\Controller
 */
class TestController extends ControllerBase {

  /**
   * Test.
   *
   * @return string
   *   Return test page render array.
   */
  public function test() {
    return ['#theme' => 'patterns_test_page'];
  }

}
