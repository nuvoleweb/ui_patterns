<?php declare(strict_types = 1);

namespace Drupal\Tests\ui_patterns\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\ui_patterns\Utils\SchemaCompatibilityChecker;

/**
 * Test description.
 *
 * @group ui_patterns
 */
final class SchemaCompatibilityCheckerTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
  }

  /**
   * Tests something.
   *
   * @dataProvider provideIsCompatibleData
   */
  public function testIsCompatible($checked_schema, $reference_schema, $expected): void {
    $validator = new SchemaCompatibilityChecker();
    self::assertEquals($expected, $validator->isCompatible($checked_schema, $reference_schema));
  }

  public function provideIsCompatibleData() {
    return [
      [['type' => 'string'], ['type' => 'string'], true]
    ];
  }

}
