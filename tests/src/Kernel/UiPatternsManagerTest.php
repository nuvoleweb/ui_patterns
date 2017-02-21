<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\isNotEmpty;
use function bovigo\assert\predicate\doesNotHaveKey;
use function bovigo\assert\predicate\hasKey;
use function bovigo\assert\predicate\equals;
use Drupal\ui_patterns\UiPatterns;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends AbstractUiPatternsTest {

  /**
   * Test UiPatternsManager::getDefinitions.
   *
   * @covers ::getDefinitions
   */
  public function testGetDefinitions() {
    $manager = $this->getPluginManager($this->getFixtureDefinitions());
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
    $manager = $this->getPluginManager($this->getFixtureDefinitions());
    $options = $manager->getPatternsOptions();

    foreach ($this->getFixtureDefinitions() as $fixture) {
      assert($options, hasKey($fixture['id']));
      assert($options[$fixture['id']], equals($fixture['label']));
    }
  }

  /**
   * Test UiPatternsManager::getPattern.
   *
   * @covers ::getPattern
   */
  public function testGetPattern() {
    $manager = UiPatterns::getManager();

    foreach ($manager->getDefinitions() as $definition) {
      $pattern = $manager->getPattern($definition['id']);
      assert($pattern->getBaseId(), equals($definition['id']));
    }
  }

}
