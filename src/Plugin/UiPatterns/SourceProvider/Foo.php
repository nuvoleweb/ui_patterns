<?php declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\SourceProvider;

use Drupal\ui_patterns\SourceProviderPluginBase;

/**
 * Plugin implementation of the source_provider.
 *
 * @SourceProvider(
 *   id = "foo",
 *   label = @Translation("Foo"),
 *   description = @Translation("Foo description.")
 * )
 */
final class Foo extends SourceProviderPluginBase {

}
