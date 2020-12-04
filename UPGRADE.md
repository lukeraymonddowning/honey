# Upgrading Honey

Here, we document everything you need to do to upgrade when there is a breaking change to Honey. Don't worry, it's
usually a matter of seconds.

## Upgrading from 0.2 to 0.3
Honey 0.3.0 removed the dependency for Alpine JS. This means that some of the class names have changed. The easiest way
to upgrade is to delete and re-publish your config file. However, if you don't want to do that, you can manually
upgrade the following fields:

- In `checks`, change `AlpineInputFilledCheck` to `JavascriptInputFilledCheck`.
- In `input_name_selectors.drivers.static.names`, change `alpine_input` to `javascript_input` (You can also change the value if you wish).
- In `input_values`, change `alpine` to `javascript`, and `AlpineInputValue` to `JavascriptInputValue`.