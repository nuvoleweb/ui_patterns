<?php

namespace Drupal\ui_patterns\Utils;

/**
 * Checks whether two schemas are compatible.
 *
 * Used for prop typing.
 *
 * Not the same as Drupal\sdc\Component\SchemaCompatibilityChecker which has
 * different rules and a different goal: validating replace mechanism.
 */
class SchemaCompatibilityChecker {

  /**
   * Checks if the second schema is compatible with the first one.
   *
   * @param array $checked_schema
   *   The schema to check compatibility against.
   * @param array $reference_schema
   *   The schema that should be compatible with the first one.
   *
   * @return bool
   */
  public function isCompatible(array $checked_schema, array $reference_schema): bool {
    $checked_schema = $this->canonicalize($checked_schema);
    $reference_schema = $this->canonicalize($reference_schema);
    if (isset($reference_schema["anyOf"])) {
      foreach ($reference_schema["anyOf"] as $schema) {
        if ($this->isCompatible($checked_schema, $schema)) {
          return TRUE;
        }
      }
      return FALSE;
    }
    if (isset($checked_schema["anyOf"])) {
      foreach ($checked_schema["anyOf"] as $schema) {
        if ($this->isCompatible($schema, $reference_schema)) {
          return TRUE;
        }
      }
      return FALSE;
    }
    if ($checked_schema["type"] !== $reference_schema["type"]) {
      return FALSE;
    }
    // Now we know $checked_schema and $reference_schema have the same type.
    // So, testing $checked_schema type is enough.
    $type = $checked_schema["type"];
    if ($type === "boolean") {
      return TRUE;
    }
    if ($type === "null") {
      return TRUE;
    }
    // Complex checking.
    if ($this->isSame($checked_schema, $reference_schema)) {
      return TRUE;
    }
    if ($type === "object") {
      return $this->isObjectCompatible($checked_schema, $reference_schema);
    }
    if ($type === "array") {
      return $this->isArrayCompatible($checked_schema, $reference_schema);
    }
    if ($type === "number") {
      return $this->isNumberCompatible($checked_schema, $reference_schema);
    }
    if ($type === "integer") {
      return $this->isNumberCompatible($checked_schema, $reference_schema);
    }
    if ($type === "string") {
      return $this->isStringCompatible($checked_schema, $reference_schema);
    }
    return FALSE;
  }

  /**
   *
   */
  protected function isSame($checked_schema, $reference_schema): bool {
    $comparaison = strcmp(
      json_encode($checked_schema),
      json_encode($reference_schema)
    );
    if ($comparaison === 0) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   *
   */
  protected function isObjectCompatible(array $checked_schema, array $reference_schema): bool {
    // @todo recursive
    return FALSE;
  }

  /**
   * Check if different arrays are compatible.
   */
  protected function isArrayCompatible(array $checked_schema, array $reference_schema): bool {
    // @todo https://json-schema.org/understanding-json-schema/reference/array#items
    // @todo https://json-schema.org/understanding-json-schema/reference/array#contains
    // @todo https://json-schema.org/understanding-json-schema/reference/array#mincontains-maxcontains
    // @tood: https://json-schema.org/understanding-json-schema/reference/array#length
    // @todo https://json-schema.org/understanding-json-schema/reference/array#uniqueness
    // @todo recursive
    return FALSE;
  }

  /**
   * Check if different numbers are compatible.
   */
  protected function isNumberCompatible(array $checked_schema, array $reference_schema): bool {
    // @todo integer are number
    // @todo https://json-schema.org/understanding-json-schema/reference/numeric#multiples
    // @todo https://json-schema.org/understanding-json-schema/reference/numeric#range
    return FALSE;
  }

  /**
   * Check if different strings are compatible.
   */
  protected function isStringCompatible(array $checked_schema, array $reference_schema): bool {
    // @todo https://json-schema.org/understanding-json-schema/reference/string#length
    // @todo https://json-schema.org/understanding-json-schema/reference/string#regexp
    // @todo https://json-schema.org/understanding-json-schema/reference/string#format
    if (isset($checked_schema["format"]) && isset($reference_schema["format"])) {
      // @todo uri, uri-reference, iri, iri-reference
      // @todo formats & sub-formats
    }
    if (!isset($checked_schema["format"]) && isset($reference_schema["format"])) {
      return FALSE;
    }
    if (isset($checked_schema["format"]) && !isset($reference_schema["format"])) {
      // A string with format is still a string.
      return TRUE;
    }
    return FALSE;
  }

  /**
   *
   */
  protected function canonicalize(array $schema): array {
    //$schema = $this->removeUselessProperties($schema);
    if (!isset($schema["type"])) {
      return $schema;
    }
    if ($schema["type"] === "object" && isset($schema["properties"])) {
      foreach ($schema["properties"] as $property_id => $property) {
        $schema["properties"][$property_id] = $this->canonicalize($property);
      }
    }
    if ($schema["type"] === "array" && isset($schema["items"])) {
      $schema["items"] = $this->canonicalize($schema["items"]);
    }
    $schema = array_filter($schema);
    ksort($schema);
    return $schema;
  }

  /**
   *
   */
  protected function removeUselessProperties(array $schema): array {
    $keys = [
      "anyOf", "allOf", "oneOf", "not", "enum", "type", '$ref', "constant",
    ];
    $keys_by_type = [
      "string" => ["minLength", "maxLength", "pattern", "format"],
      "number" => ["minimum", "maximum", "exclusiveMinimum", "exclusiveMaximum", "multipleOf"],
      "integer" => ["minimum", "maximum", "exclusiveMinimum", "exclusiveMaximum", "multipleOf"],
      "boolean" => [],
      "null" => [],
      "array" => ["minItems", "maxItems", "items", "additionalItems", "uniqueItems"],
      "object" => ["properties", "additionalProperties", "required", "minProperties", "maxProperties", "dependencies", "patternProperties"],
    ];
    if (isset($schema["type"])) {
      $type = $schema["type"];
      $keys = array_merge($keys, $keys_by_type[$type]);
    }
    return array_intersect_key($schema, array_flip($keys));
  }

}
