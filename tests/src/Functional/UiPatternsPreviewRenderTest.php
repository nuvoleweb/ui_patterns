<?php

namespace Drupal\Tests\ui_patterns\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\ui_patterns\Traits\TwigDebugTrait;

/**
 * Test pattern preview rendering.
 *
 * @group ui_patterns
 */
class UiPatternsPreviewRenderTest extends BrowserTestBase {

  /**
   * Default theme. See https://www.drupal.org/node/3083055.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Disable schema validation when running tests.
   *
   * @var bool
   *
   * @todo: Fix this by providing actual schema validation.
   */
  protected $strictConfigSchema = FALSE;

  use TwigDebugTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'ui_patterns',
    'ui_patterns_library',
    'ui_patterns_render_test',
  ];

  /**
   * Tests pattern preview suggestions.
   */
  public function testPatternPreviewSuggestions() {
    $assert_session = $this->assertSession();

    $this->enableTwigDebugMode();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    $this->drupalGet('/patterns');

    // Assert correct variant suggestions.
    $suggestions = [
      'pattern-foo--variant-default--preview.html.twig',
      'pattern-foo--variant-default.html.twig',
      'pattern-foo--preview.html.twig',
      'pattern-foo.html.twig',
      'pattern-foo-bar--variant-default--preview.html.twig',
      'pattern-foo-bar--variant-default.html.twig',
      'pattern-foo-bar--preview.html.twig',
      'pattern-foo-bar.html.twig',
    ];
    foreach ($suggestions as $suggestion) {
      $assert_session->responseContains($suggestion);
    }
  }

}
