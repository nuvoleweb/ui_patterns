# UI Patterns
[![Build Status](https://travis-ci.org/nuvoleweb/ui_patterns.svg?branch=8.x-1.x)](https://travis-ci.org/nuvoleweb/ui_patterns)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nuvoleweb/ui_patterns/badges/quality-score.png?b=8.x-1.x)](https://scrutinizer-ci.com/g/nuvoleweb/ui_patterns/?branch=8.x-1.x)
[![Documentation Status](https://readthedocs.org/projects/ui-patterns/badge/?version=8.x-1.x)](http://ui-patterns.readthedocs.io/en/8.x-1.x/?badge=8.x-1.x)

Define self-contained UI patterns as Drupal plugins and use them seamlessly in your [panels](https://www.drupal.org/project/panels), 
[field groups](https://www.drupal.org/project/field_group) or [Display Suite](https://www.drupal.org/project/ds) view modes.

The module also generates a pattern library page to be used as documentation for content editors or as a showcase 
for business and clients:

![Showcase page example](https://raw.githubusercontent.com/nuvoleweb/ui_patterns/8.x-1.x/docs/_static/pattern-library.png)

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
