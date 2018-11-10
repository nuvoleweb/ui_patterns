<?php

namespace Drupal\Tests\ui_patterns_library\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test patterns overview page.
 *
 * @group ui_patterns_library
 */
class UiPatternsLibraryOverviewTest extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'ui_patterns',
    'ui_patterns_library',
    'ui_patterns_library_module_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->container->get('theme_installer')->install(['ui_patterns_library_theme_test']);
    $this->container->get('theme_handler')->setDefault('ui_patterns_library_theme_test');
    $this->container->set('theme.registry', NULL);
  }

  /**
   * Tests overview page.
   */
  public function testOverviewPage() {
    $assert_session = $this->assertSession();

    $user = $this->drupalCreateUser(['access patterns page']);
    $this->drupalLogin($user);
    $this->drupalGet('/patterns');

    // sleep(10000);
    // Assert patterns list.
    $assert_session->elementContains('css', 'h2', 'Available patterns');

    $patterns = [
      1 => ['anchor' => '#simple', 'label' => 'Simple'],
      2 => ['anchor' => '#with_variants', 'label' => 'With variants'],
    ];

    foreach ($patterns as $index => $pattern) {
      $this->assertListLink($index, $pattern['label'], $pattern['anchor']);
    }

    // Assert pattern preview content.
    $patterns = [
      [
        'name' => 'simple',
        'label' => 'Simple',
        'description' => 'A simple pattern',
        'field_rows' => [
          ['field', 'Field', 'string', 'Field description'],
        ],
        'preview' => 'Simple pattern field',
      ],
      [
        'name' => 'with_variants',
        'label' => 'With variants',
        'description' => 'Pattern with variants',
        'field_rows' => [
          ['field', 'Field', 'string', 'Field description'],
        ],
        'preview' => 'With variants pattern field',
      ],
    ];

    foreach ($patterns as $pattern) {
      $root = '.pattern-preview--' . $pattern['name'];

      // Assert pattern preview.
      $assert_session->elementExists('css', $root);
      $assert_session->elementContains('css', "$root > h3", $pattern['label']);
      $assert_session->elementContains('css', "$root > p", $pattern['description']);

      // Assert fields table content.
      foreach ($pattern['field_rows'] as $row) {
        foreach ($row as $index => $item) {
          $child = $index + 1;
          $this->assertSession()->elementContains('css', "$root > table > tbody > tr > td:nth-child($child)", $item);
        }
      }

      // Assert preview content.
      $assert_session->elementContains('css', "$root > fieldset", $pattern['preview']);
    }
  }

  /**
   * Assert pattern overview list link.
   *
   * @param int $index
   *   Position on list.
   * @param string $label
   *   Pattern label.
   * @param string $anchor
   *   Pattern anchor.
   *
   * @throws \Behat\Mink\Exception\ElementHtmlException
   */
  protected function assertListLink($index, $label, $anchor) {
    $this->assertSession()->elementContains('css', "ul > li:nth-child($index) > a", $label);
    $this->assertSession()->elementAttributeContains('css', "ul > li:nth-child($index) > a", 'href', $anchor);
  }

}
