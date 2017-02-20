<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use function bovigo\assert\predicate\doesNotHaveKey;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends AbstractUiPatternsTest {

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
   * Test UiPatternsManager::getDefinitions.
   *
   * @covers ::getDefinitions
   */
  public function testGetDefinitions() {
    $manager = $this->getFixturePluginManager();
    $definitions = $manager->getDefinitions();
    assert($definitions, isNotEmpty());

    foreach ($this->getFixtureDefinitions() as $fixture_id => $fixture) {
      assert($definitions, doesNotHaveKey($fixture_id));
      assert($definitions, hasKey($fixture['id']));
    }
  }

  /**
   * Test UiPatternsManager::getPatternsOptions.
   *
   * @covers ::getPatternsOptions
   */
  public function testGetPatternsOptions() {
    $manager = $this->getFixturePluginManager();
    $options = $manager->getPatternsOptions();

    foreach ($this->getFixtureDefinitions() as $fixture) {
      assert($options, hasKey($fixture['id']));
      assert($options[$fixture['id']], equals($fixture['label']));
    }
  }

}
