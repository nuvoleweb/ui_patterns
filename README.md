# UI Patterns

[![Join the chat at https://gitter.im/nuvoleweb/ui_patterns](https://badges.gitter.im/nuvoleweb/ui_patterns.svg)](https://gitter.im/nuvoleweb/ui_patterns?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://travis-ci.org/nuvoleweb/ui_patterns.svg?branch=8.x-1.x)](https://travis-ci.org/nuvoleweb/ui_patterns)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nuvoleweb/ui_patterns/badges/quality-score.png?b=8.x-1.x)](https://scrutinizer-ci.com/g/nuvoleweb/ui_patterns/?branch=8.x-1.x)
[![Documentation Status](https://readthedocs.org/projects/ui-patterns/badge/?version=8.x-1.x)](http://ui-patterns.readthedocs.io/en/8.x-1.x/?badge=8.x-1.x)

Define and expose self-contained UI patterns as Drupal plugins and use them seamlessly as drop-in templates for 
[panels](https://www.drupal.org/project/panels), [field groups](https://www.drupal.org/project/field_group), views,
[Display Suite](https://www.drupal.org/project/ds) view modes and field templates. 

The module generates a pattern library page to be used as documentation for content editors or as a showcase for 
business and clients.

The UI Patterns module also integrates with with tools like [PatternLab](http://patternlab.io/) or modules like 
[Component Libraries](https://www.drupal.org/project/components) thanks to 
[definition overrides](http://ui-patterns.readthedocs.io/en/8.x-1.x/content/patterns-definition.html#override-patterns-behavior).

![Showcase page example](https://raw.githubusercontent.com/nuvoleweb/ui_patterns/8.x-1.x/docs/images/pattern-library.png)

## Project overview

The UI Patterns project provides 5 modules:

- **UI Patterns**: the main module, its purpose is to expose patterns to the Drupal rendering system and provide a pattern library overview page, available at `/patterns`. [Learn more](http://ui-patterns.readthedocs.io/en/8.x-1.x/content/patterns-definition.html)
- **UI Patterns Field Group**: allows to use patterns to format field groups provided by the [Field group](https://www.drupal.org/project/field_group) module. [Learn more](http://ui-patterns.readthedocs.io/en/8.x-1.x/content/field-group.html)
- **UI Patterns Layouts**: allows to use patterns as layouts provided by the [Layout plugin](https://www.drupal.org/project/layout_plugin) module. This allows patterns to be used on [Display Suite](https://www.drupal.org/project/ds) view modes or on [panels](https://www.drupal.org/project/panels) out of the box. [Learn more](http://ui-patterns.readthedocs.io/en/8.x-1.x/content/layout-plugin.html)
- **UI Patterns Display Suite**: allows to use patterns to format [Display Suite](https://www.drupal.org/project/ds) field templates. [Learn more](http://ui-patterns.readthedocs.io/en/8.x-1.x/content/field-templates.html)
- **UI Patterns Views**: allows to use patterns as Views row templates. [Learn more](http://ui-patterns.readthedocs.io/en/8.x-1.x/content/views.html)

## Try it out!

Download and install the [Bootstrap Patterns](https://github.com/nuvoleweb/bootstrap_patterns) theme on a vanilla Drupal
8 installation to quickly try out the UI Patterns module.


## Documentation

Documentation is hosted on [Read the Docs](https://readthedocs.org/) and available [here](http://ui-patterns.readthedocs.io/en/8.x-1.x).

To build the documentation make sure you setup your environment by following
[these instructions](http://read-the-docs.readthedocs.io/en/latest/getting_started.html) first.

After setting up your environment run:

```
$ cd docs
$ make html
```

The documentation is then available at ``./docs/_build/html/index.html``.

If you want to contribute documentation you can setup and auto-compile that will watch for documentation changes by running:

```
$ make livehtml
```

You can then preview the compiled documentation at ``http://127.0.0.1:8000``.
