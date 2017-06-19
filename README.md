# SilverWare Google Module

[![Latest Stable Version](https://poser.pugx.org/silverware/google/v/stable)](https://packagist.org/packages/silverware/google)
[![Latest Unstable Version](https://poser.pugx.org/silverware/google/v/unstable)](https://packagist.org/packages/silverware/google)
[![License](https://poser.pugx.org/silverware/google/license)](https://packagist.org/packages/silverware/google)

Provides Google Analytics integration, Google site verification, and a 
[Google+ sharing button][googlesharebutton] for use with [SilverWare][silverware].

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Issues](#issues)
- [Contribution](#contribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]
- [SilverWare Social Module][silverware-social]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/google
```

## Configuration

As with all SilverStripe modules, configuration is via YAML.  Extensions are applied to `Page` and
`ContentController` for Google integration.  An extension is also applied to site configuration which
adds a tab for Google settings to the SilverWare Services tab.

### Google API Language

By default, the Google API will automatically detect the appropriate language to use for buttons
and widgets based on the user locale. However, if you would like to force the API to use a particular language,
you can do so using site configuration. Go to Settings > SilverWare > Services > Google > Google API, and
select the desired language from the dropdown field.

## Usage

### Google Sharing Button

![Google Sharing Button](http://i.imgur.com/MLb5nVf.png)

Provided is a `GoogleSharingButton` which is used with the `SharingComponent`
from the [SilverWare Social Module][silverware-social]. Simply add this button as a child of
`SharingComponent` using the site tree, and your pages will now
be able to be shared via Google+.

For more information, see the [Google documentation][googlesharebutton].

### Google Analytics

This module provides automatic integration with Google Analytics to measure page views
and statistics within your SilverWare app. To do this, you will first need to create
a [Google Analytics tracking ID][googleanalyticsid].

After you have a tracking ID, simply paste the ID into the
Google Analytics section of site configuration, and check the "Enabled" box:

![Google Analytics](http://i.imgur.com/Lu7HPo9.png)

After saving the settings, you can verify tracking is
working by using the [Real-Time][googleanalyticsrt] feature in your Google Analytics account.

### Google Site Verification

Some Google features (such as [Search Console][googlesearchconsole]) require [verification of site ownership][googlesiteverification].
Google performs this check by looking for a verification code within the source of the website.

After adding your site as a property to Search Console, Google will ask that you
verify ownership. You should see two tabs, "Recommended method" and "Alternate methods":

1. Click the "Alternate methods" tab.
2. Click on "HTML tag" from the methods that appear.
3. Copy the meta tag code that appears, and paste it into the "Verification code" field in site configuration.
4. Save the site configuration.

It does not matter if you paste the entire meta tag HTML, as it will be detected and only the
verification code will remain after saving.

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](http://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](http://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[composer]: https://getcomposer.org
[googlesharebutton]: https://developers.google.com/+/web/share/
[googleanalyticsid]: https://support.google.com/analytics/answer/1008080
[googleanalyticsrt]: https://support.google.com/analytics/answer/1638635
[googlesiteverification]: https://support.google.com/webmasters/answer/35179
[googlesearchconsole]: https://www.google.com/webmasters/tools
[silverware]: https://github.com/praxisnetau/silverware
[silverware-social]: https://github.com/praxisnetau/silverware-social
[issues]: https://github.com/praxisnetau/silverware-google/issues
