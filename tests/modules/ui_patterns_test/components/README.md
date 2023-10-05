# alert

A medium complexity component for usual testing, using component() Twig function.

❌ Not a valid SDC component because of the component() Twig function.

# blockquote

A simple component for basic testing.
No props. No variants.

✅ Valid SDC component. The additions can be ignored.

I was forced to write this anyway, because the component come from a module:

```
props:
  type: object
  properties: {}
```

# button

A lot of variants. Props (with implicit typing) & slots.
With explicit template path (local, without namespace, the filename from UI Patterns 1.x)

❌ Not a valid SDC component, because of the explicit template path.

SDC do a fatal error:

> Drupal\sdc\Exception\InvalidComponentException: Unable to find the Twig template for the component "ui_patterns_test:button".

# card

A complex component with a template by variant. A story calling another component ("button").

Props with implicit typing and default values.

✅ Valid SDC component. The additions can be ignored.

# close_button

Here in order to test the component() Twig function in `alert`.

Only props, no slots.

There was initially an issue about the use of "\_" and "-" in component ID and template filename. See issue: [SDC should use dashes in file names](https://www.drupal.org/project/drupal/issues/3379527), but we renamed the template.

✅ Valid SDC component. The additions can be ignored.

# figure

1 prop (with explicit typing) and slots. No variants.

Replace `replaced_figure`

❓ Not sure if the explicit typing will make this component a valid SDC component.

# my-widget

A more "traditional" SDC example with JSON schema examples instead of stories, and Twig blocks for slots.

2 variants.

✅ Valid SDC component. The additions can be ignored.

# progress

With explicit template path (in a subfolder, without namespace, expected filename)

❌ Not a valid SDC component, because of explicit template path.

# replaced_figure

To test replacement mechanism

✅ Valid SDC component.
