<?php

namespace Drupal\ui_patterns_test\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;

/**
 * Defines Fields API pattern source plugin.
 *
 * @UiPatternsSource(
 *   id = "test_source",
 *   label = @Translation("Test source"),
 *   provider = "ui_patterns_test",
 *   tags = {
 *     "test"
 *   }
 * )
 */
class TestSource extends UiPatternsSourceBase {

}
