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
    if ($this->isSame($checked_schema, $reference_schema)) {
      return TRUE;
    }
    if (isset($checked_schema["type"]) && isset($reference_schema["type"])) {
      return $this->isTypeCompatible($checked_schema, $reference_schema);
    }
    if (isset($checked_schema["anyOf"]) || isset($reference_schema["anyOf"])) {
      return $this->isAnyOfCompatible($checked_schema, $reference_schema);
    }
    return FALSE;
  }

  /**
   *
   */
  protected function isSame($checked_schema, $reference_schema): bool {
    return (serialize($checked_schema) === serialize($reference_schema));
  }

  /**
   *
   */
  protected function isTypeCompatible($checked_schema, $reference_schema): bool {
    if (is_array($checked_schema["type"]) || is_array($checked_schema["type"])) {
      // Because of self::resolveMultipleTypes() we are not supposed to meet this
      // situation.
      return FALSE;
    }
    if ($checked_schema["type"] !== $reference_schema["type"]) {
      // Integers are numbers.
      if (!($checked_schema["type"] === "integer" && $reference_schema["type"] === "number")) {
        return FALSE;
      }
    }
    // Now we know $checked_schema and $reference_schema have the same type.
    // So, testing $checked_schema type is enough.
    return match ($checked_schema["type"]) {
      'null' => TRUE,
      'boolean' => TRUE,
      'object' => $this->isObjectCompatible($checked_schema, $reference_schema),
      'array' => $this->isArrayCompatible($checked_schema, $reference_schema),
      'number' => $this->isNumberCompatible($checked_schema, $reference_schema),
      'integer' => $this->isNumberCompatible($checked_schema, $reference_schema),
      'string' => $this->isStringCompatible($checked_schema, $reference_schema),
    };
  }

  /**
   *
   */
  protected function isAnyOfCompatible($checked_schema, $reference_schema): bool {
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
    }
    return FALSE;
  }

  /**
   *
   */
  protected function isObjectCompatible(array $checked_schema, array $reference_schema): bool {
    if (isset($checked_schema["properties"]) && isset($reference_schema["properties"])) {
      // @todo
    }
    if (isset($checked_schema["patternProperties"]) && isset($reference_schema["patternProperties"])) {
      // @todo
    }
    return TRUE;
  }

  /**
   * Check if different arrays are compatible.
   */
  protected function isArrayCompatible(array $checked_schema, array $reference_schema): bool {
    // https://json-schema.org/understanding-json-schema/reference/array#items
    if (isset($checked_schema["items"]) && isset($reference_schema["items"])) {
      if (!$this->isCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    // FALSE if at least one of those tests is FALSE.
    // @todo https://json-schema.org/understanding-json-schema/reference/array#contains
    // @todo https://json-schema.org/understanding-json-schema/reference/array#mincontains-maxcontains
    // @todo https://json-schema.org/understanding-json-schema/reference/array#length
    // @todo https://json-schema.org/understanding-json-schema/reference/array#uniqueness
    return TRUE;
  }

  /**
   * Check if different numbers are compatible.
   */
  protected function isNumberCompatible(array $checked_schema, array $reference_schema): bool {
    // FALSE if at least one of those tests is FALSE.
    if (isset($reference_schema["enum"])) {
      if (!$this->isEnumCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    // @todo https://json-schema.org/understanding-json-schema/reference/numeric#multiples
    // @todo https://json-schema.org/understanding-json-schema/reference/numeric#range
    return TRUE;
  }

  /**
   * Check if different strings are compatible.
   */
  protected function isStringCompatible(array $checked_schema, array $reference_schema): bool {
    // FALSE if at least one of those tests is FALSE.
    if (isset($reference_schema["pattern"])) {
      if (!$this->isStringPatternCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (isset($reference_schema["format"])) {
      if (!$this->isStringFormatCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (isset($reference_schema["enum"])) {
      if (!$this->isEnumCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (isset($reference_schema["minLength"])) {
      if (!$this->isMinLengthCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (isset($reference_schema["maxLength"])) {
      if (!$this->isMaxLengthCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * See: https://json-schema.org/understanding-json-schema/reference/string#regexp.
   */
  protected function isStringPatternCompatible(array $checked_schema, array $reference_schema): bool {
    if (!isset($checked_schema["pattern"])) {
      return FALSE;
    }
    $checked_pattern = ltrim($checked_schema["pattern"], "^");
    $checked_pattern = rtrim($checked_schema["pattern"], "$");
    // @todo $reference_pattern = str_replace(["(", ")", ",
    if (str_contains($reference_schema["pattern"], $checked_pattern)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * See: https://json-schema.org/understanding-json-schema/reference/string#format.
   */
  protected function isStringFormatCompatible(array $checked_schema, array $reference_schema): bool {
    if (!isset($checked_schema["format"])) {
      return FALSE;
    }
    $checked_format = $checked_schema["format"];
    $reference_format = $reference_schema["format"];
    if ($checked_format == $reference_format) {
      return TRUE;
    }
    // Ex: an uri is also a valid uri-reference
    // Ex: an uri-reference is also a valid iri-reference.
    $compatibility_map = [
      "uri" => [
        "uri-reference",
        "iri-reference",
        "iri",
      ],
      "iri" => [
        "iri-reference",
      ],
      "uri-reference" => [
        "iri-reference",
      ],
      "email" => [
        "idn-email",
      ],
      // @todo add others formats.
    ];
    if (array_key_exists($checked_format, $compatibility_map)) {
      return in_array($reference_format, $compatibility_map[$checked_format]);
    }
    return FALSE;
  }

  /**
   *
   */
  protected function isMinLengthCompatible(array $checked_schema, array $reference_schema): bool {
    if (!isset($checked_schema["minLength"])) {
      return FALSE;
    }
    return ($checked_schema["minLength"] >= $reference_schema["minLength"]);
  }

  /**
   *
   */
  protected function isMaxLengthCompatible(array $checked_schema, array $reference_schema): bool {
    if (!isset($checked_schema["maxLength"])) {
      return FALSE;
    }
    return ($checked_schema["maxLength"] <= $reference_schema["maxLength"]);
  }

  /**
   *
   */
  protected function isEnumCompatible(array $checked_schema, array $reference_schema): bool {
    if (!isset($checked_schema["enum"])) {
      return FALSE;
    }
    // @todo
    return FALSE;
  }

  /**
   * @todo Make it public and unit testable independently?
   */
  protected function canonicalize(array $schema): array {
    $schema = $this->removeUselessProperties($schema);
    if (isset($schema["type"])) {
      $schema = $this->canonicalizeType($schema);
    }
    if (isset($schema["anyOf"])) {
      foreach ($schema["anyOf"] as $index => $sub_schema) {
        $schema["anyOf"][$index] = $this->canonicalize($sub_schema);
      }
    }
    $schema = array_filter($schema);
    ksort($schema);
    return $schema;
  }

  /**
   *
   */
  protected function canonicalizeType(array $schema): array {
    if (is_array($schema["type"])) {
      $schema = $this->resolveMultipleTypes($schema);
    }
    if ($schema["type"] === "object" && isset($schema["properties"])) {
      foreach ($schema["properties"] as $property_id => $property) {
        $schema["properties"][$property_id] = $this->canonicalize($property);
      }
    }
    if ($schema["type"] === "array" && isset($schema["items"])) {
      $schema["items"] = $this->canonicalize($schema["items"]);
    }
    return $schema;
  }

  /**
   *
   */
  protected function resolveMultipleTypes(array $schema): array {
    if (!is_array($schema["type"])) {
      return $schema;
    }
    $schemas = [
      "anyOf" => [],
    ];
    foreach ($schema["type"] as $index => $type) {
      $schemas["anyOf"][$index] = array_merge(
        $schema,
        [
          "type" => $type,
        ]
      );
    }
    return $schemas;
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
    if (isset($schema["type"]) && is_string($schema["type"])) {
      $type = $schema["type"];
      if (array_key_exists($type, $keys_by_type)) {
        $keys = array_merge($keys, $keys_by_type[$type]);
      }
    }
    return array_intersect_key($schema, array_flip($keys));
  }

}
