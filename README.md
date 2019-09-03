# MailPoet WPML fixture

This is a WordPress plugin that brutally implements what MailPoet is missing: a before send filter for the confirmation mail.

This plugin manually adds a line that applies the filter, so you're able to intercept it later in your code and customize it at your will.

The main reason behind this is that MailPoet is not compatible with WPML, which leads to a non-translatable confirmation mail.

## Building

To package the plugin we use Gulp together with some other modules, so the only thing you need to run is:

```shell
yarn
```

followed by:

```
yarn package
```
