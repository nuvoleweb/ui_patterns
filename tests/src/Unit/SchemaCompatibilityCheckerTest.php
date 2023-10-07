<?php declare(strict_types = 1);

namespace Drupal\Tests\ui_patterns\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\ui_patterns\Utils\SchemaCompatibilityChecker;
use Drupal\Component\Serialization\Yaml;

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
  public function testIsCompatible($test_name, $checked_schema, $reference_schema, $expected_result): void {
    ob_end_clean();
    print("\n" . $test_name . ": ");
    ob_start();
    $validator = new SchemaCompatibilityChecker();
    self::assertEquals($expected_result, $validator->isCompatible($checked_schema, $reference_schema));
  }

  public function provideIsCompatibleData() {
    $data = [];
    $sources = Yaml::decode(file_get_contents(__DIR__ . "/schema_compatibility_checker_data.yml"));
    foreach ($sources as $source) {
      foreach ($source["tests"] as $test) {
        $data[] = [
          $source["label"] . ": " . $test["label"] . " is " . ($test["result"] ? "OK" : "KO"),
          $test["schema"],
          $source["schema"],
          (bool) $test["result"],
        ];
      }
    };
    return $data;
  }

}
