<?php

namespace Drupal\Tests\ui_patterns_field_group\Unit;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Tests\UnitTestCase;
use Drupal\ui_patterns_field_group\Utility\EntityFinder;

/**
 * Test entity finder utility.
 *
 * @group ui_patterns
 */
class EntityFinderTest extends UnitTestCase {

  /**
   * Test find entity from fields.
   *
   * @dataProvider fieldsDataProvider
   */
  public function testFindEntityFromFields($fields, $expected) {
    $finder = new EntityFinder();
    $entity = $finder->findEntityFromFields($fields);
    $this->assertEquals($expected, $entity);
  }

  /**
   * Test data provider.
   *
   * @return array
   *   Test data.
   */
  public function fieldsDataProvider() {
    $good = $this->createMock(ContentEntityBase::class);
    $bad = new \stdClass();

    return [
      // Found with singe value per field.
      [
        'fields' => [
          'foo' => ['#object' => $good],
          'bar' => ['#object' => $bad],
        ],
        'expected' => $good,
      ],

      // Found with singe value per field.
      [
        'fields' => [
          'bar' => ['#object' => $bad],
          'foo' => ['#object' => $good],
        ],
        'expected' => $good,
      ],

      // Found with multiple values per field.
      [
        'fields' => [
          'foo' => [['#object' => $good]],
          'bar' => [['#object' => $bad]],
        ],
        'expected' => $good,
      ],

      // Found with multiple values per field.
      [
        'fields' => [
          'bar' => [
            ['#object' => $bad],
            ['#object' => $good],
          ],
          'foo' => [
            ['#object' => $bad],
            ['#object' => $bad],
          ],
        ],
        'expected' => $good,
      ],

      // Found with one empty array field and multiple values per field.
      [
        'fields' => [
          'foo' => [
            [],
            ['#object' => $good],
          ],
        ],
        'expected' => $good,
      ],

      // Found with one empty null field and multiple values per field.
      [
        'fields' => [
          'foo' => [NULL, ['#object' => $good],
          ],
        ],
        'expected' => $good,
      ],

      // Not found with one empty null field and multiple values per field.
      [
        'fields' => [
          'foo' => [NULL, ['#object' => $bad],
          ],
        ],
        'expected' => NULL,
      ],
    ];
  }

}
