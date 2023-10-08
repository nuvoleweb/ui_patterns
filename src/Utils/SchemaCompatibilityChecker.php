<?php

namespace Drupal\ui_patterns\Utils;

use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Random\SimpleRandom;

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
   *   The schema that should be compatible with the other one.
   * @param array $reference_schema
   *   The schema to check compatibility against.
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
      // Integers are numbers, but numbers are not always integer.
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
      'integer' => $this->isIntegerCompatible($checked_schema, $reference_schema),
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
    // FALSE if at least one of those tests is FALSE.
    if (!isset($checked_schema["properties"]) && isset($reference_schema["properties"])) {
      return FALSE;
    }
    if (!isset($checked_schema["patternProperties"]) && isset($reference_schema["patternProperties"])) {
      return FALSE;
    }
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
    // FALSE if at least one of those tests is FALSE.
    if (!isset($checked_schema["items"]) && isset($reference_schema["items"])) {
      return FALSE;
    }
    // https://json-schema.org/understanding-json-schema/reference/array#items
    if (isset($checked_schema["items"]) && isset($reference_schema["items"])) {
      if (!$this->isCompatible($checked_schema["items"], $reference_schema["items"])) {
        return FALSE;
      }
    }
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
    if ($reference_schema["type"] === "integer") {
      // Integers are always numbers, but numbers are not always integer.
      return FALSE;
    }
    return $this->isNumericCompatible($checked_schema, $reference_schema);
  }

  /**
   * Check if different integers are compatible.
   */
  protected function isIntegerCompatible(array $checked_schema, array $reference_schema): bool {
    return $this->isNumericCompatible($checked_schema, $reference_schema);
  }

  /**
   * Rules shared by numbers and integers.
   */
  protected function isNumericCompatible(array $checked_schema, array $reference_schema): bool {
    // FALSE if at least one of those tests is FALSE.
    if (array_key_exists("enum", $reference_schema)) {
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
    if (array_key_exists("pattern", $reference_schema)) {
      if (!$this->isStringPatternCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (array_key_exists("format", $reference_schema)) {
      if (!$this->isStringFormatCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (array_key_exists("enum", $reference_schema)) {
      if (!$this->isEnumCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (array_key_exists("minLength", $reference_schema)) {
      if (!$this->isMinLengthCompatible($checked_schema, $reference_schema)) {
        return FALSE;
      }
    }
    if (array_key_exists("maxLength", $reference_schema)) {
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
    if (!array_key_exists("pattern", $checked_schema)) {
      return FALSE;
    }
    // Is checked schema pattern and sub pattern of reference schema?
    $example = $this->generateExampleFromPattern($checked_schema["pattern"]);
    $result = preg_match("/" . $reference_schema["pattern"] . "/", $example);
    if ($result !== 1) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   *
   */
  protected function generateExampleFromPattern(string $pattern): string {
    $lexer = new Lexer($pattern);
    $gen = new SimpleRandom(10007);
    $result = '';
    $parser = new Parser($lexer, new Scope(), new Scope());
    $parser->parse()->getResult()->generate($result, $gen);
    return $result;
  }

  /**
   * See: https://json-schema.org/understanding-json-schema/reference/string#format.
   */
  protected function isStringFormatCompatible(array $checked_schema, array $reference_schema): bool {
    if (!array_key_exists("format", $checked_schema)) {
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
    if (!array_key_exists("minLength", $checked_schema)) {
      return FALSE;
    }
    return ($checked_schema["minLength"] >= $reference_schema["minLength"]);
  }

  /**
   *
   */
  protected function isMaxLengthCompatible(array $checked_schema, array $reference_schema): bool {
    if (!array_key_exists("maxLength", $checked_schema)) {
      return FALSE;
    }
    return ($checked_schema["maxLength"] <= $reference_schema["maxLength"]);
  }

  /**
   *
   */
  protected function isEnumCompatible(array $checked_schema, array $reference_schema): bool {
    if (!array_key_exists("enum", $checked_schema)) {
      return FALSE;
    }
    if (empty($reference_schema["enum"])) {
      return TRUE;
    }
    if (count($checked_schema["enum"]) === count($reference_schema["enum"])) {
      $diff = array_diff($checked_schema["enum"], $reference_schema["enum"]);
      return ($diff === []);
    }
    if (count($checked_schema["enum"]) > count($reference_schema["enum"])) {
      return FALSE;
    }
    if (count($checked_schema["enum"]) < count($reference_schema["enum"])) {
      $diff = array_diff($reference_schema["enum"], $checked_schema["enum"]);
      return (count($diff) >= 0);
    }
    return FALSE;
  }

  /**
   * @todo Make it public and unit testable independently?
   */
  protected function canonicalize(array $schema): array {
    $schema = $this->keepOnlyUsefulProperties($schema);
    if (array_key_exists("type", $schema)) {
      $schema = $this->canonicalizeType($schema);
    }
    if (array_key_exists("anyOf", $schema)) {
      foreach ($schema["anyOf"] as $index => $sub_schema) {
        $schema["anyOf"][$index] = $this->canonicalize($sub_schema);
      }
    }
    ksort($schema);
    return $schema;
  }

  /**
   *
   */
  protected function canonicalizeType(array $schema): array {
    if (!isset($schema["type"])) {
      return $schema;
    }
    if (is_array($schema["type"])) {
      $schema = $this->resolveMultipleTypes($schema);
      return $this->canonicalize($schema);
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
      $sub_schema = $schema;
      $sub_schema["type"] = $type;
      $schemas["anyOf"][$index] = $sub_schema;
    }
    return $schemas;
  }

  /**
   *
   */
  protected function keepOnlyUsefulProperties(array $schema): array {
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
