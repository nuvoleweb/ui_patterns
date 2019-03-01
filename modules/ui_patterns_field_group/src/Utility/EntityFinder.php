<?php

namespace Drupal\ui_patterns_field_group\Utility;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Find a content entity in a render array if keyed by '#object'.
 */
class EntityFinder {

  /**
   * Find content entity object if any.
   *
   * @param array $fields
   *   Fields array.
   *
   * @return \Drupal\Core\Entity\ContentEntityBase|null
   *   Entity object or NULL if none found.
   */
  public function findEntityFromFields(array $fields) {

    $iterator = new \RecursiveIteratorIterator(
      new \RecursiveArrayIterator($fields),
      \RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $key => $value) {
      if ($key === '#object' && $value instanceof ContentEntityBase) {
        return $value;
      }
    }
  }

}
