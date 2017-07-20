# UI Patterns Variants

Description
---

The UI Pattern Variants module allows site builders and end users to choose
variants defined by the pattern. Pattern developers can build components with
variants and use this module to expose an interface for those variants.

Installation
---

Install this module like any other module. [See Drupal Documentation](https://drupal.org/documentation/install/modules-themes/modules-8)

Configuration
---

After enabling this module a `variants` key is available to each pattern YAML
file in which you can define each possible variant and configurable values.

eg:
```
#
# Example Pattern
#
example_pattern:
  label: Example
  description: "Example pattern with variant."
  variants:
    variant_name:
      label: "Variant Name"
      description: "A description of what this variant does"
      text_input: true
      default: option-key-2
      options:
        option-key-1: "First possible option"
        option-key-2: "Second possible option"
  fields:
  ...
```

Each of the configured variants will be available to the variant twig file
through a global variants variable.

eg:
```
{{ variants.variant_name }}
```

The options key is optional but is useful for enumerating the possible values
that the twig file is capable of handling. The key of the options array is 
the value that is passed in to the template. For example, if the administrator 
selected "Second possible option" in the form settings `{{ variants.variant_name }}`
would have a value of `option-key-2`.

The default key is also optional and will be used if the variant output has
no valid output option/value.

`text_input` key is optional but can be defined and set to true to allow for a text input value instead of the options.

Implementation
---

*Layout Discovery Plugin*

*Field Groups*

*Views*

*Panels*


Troubleshooting
---

If you are experiencing issues with this module try reverting the feature first. If you are still experiencing issues try posting an issue on the GitHub issues page or join the gitter conversation at: https://gitter.im/nuvoleweb/ui_patterns
