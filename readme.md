# Sa11y on WordPress
This is the development environment for [Sa11y](https://github.com/ryersondmp/sa11y).

Documentation for WordPress plugin coming soon.

## How to report bugs
Report bugs by creating a new issue. If you do not have a GitHub account, please submit your issue using this [Google Form.](https://forms.gle/sjzK9XykETaoqZv99)

## Generate a fresh language.pot file
`wp i18n make-pot . languages/sa11y-i18n.pot --domain=sa11y-i18n`

## Authors
Created and maintained by [Adam Chaboryk](https://github.com/adamchaboryk).

**Contact:** [adam.chaboryk@torontomu.ca](mailto:adam.chaboryk@torontomu.ca)

## Changelog

### 1.1.7
* Upgrade to Sa11y 3.2.1
* New: The Images tab makes it easy to review all images and their corresponding alt text within a page for accuracy and quality.
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

### 1.1.6
* Upgrade to Sa11y 3.0.8
* Minor bug fixes.
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

### 1.1.5
* Upgrade to Sa11y 3.0.6
* Added new Export Results prop (default off)
* New machine translations: Bulgarian, Hungarian, Korean, Slovak
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

### 1.1.4
* Upgrade to Sa11y 3.0.6
* Added new Export Results prop.
* New machine translations: Bulgarian, Hungarian, Korean, Slovak
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

### 1.1.3
* Upgrade to Sa11y 3.0.3
* Added Spanish and several machine translations.
* New ruleset to flag non-descript DOI (digital object identifier) links.
* Check out the [latest release notes for Sa11y 3.0.3!](https://github.com/ryersondmp/sa11y/releases/tag/3.0.3)

### 1.1.2
* Bug fixes.

### 1.1.1
* Upgrade to Sa11y 3!
* Content editors now have the ability to temporarily dismiss warnings, preview pages with various colour filters, and new admin settings.
* German translation added.
* Multisite support added. Multisite admins can create global exclusions and provide custom defaults.
* Check out the [latest release notes for Sa11y 3.0!](https://github.com/ryersondmp/sa11y/releases/tag/3.0.0)

### 1.1.0
* Improved Page Outline panel.
* Various bug fixes.
* Upgrade to Sa11y 2.3.5
* View [latest release notes for Sa11y](https://github.com/ryersondmp/sa11y/releases/)

### 1.0.9
* List of headings in the Page Outline panel are now clickable.
* Swedish translation added.
* View [full release notes for Sa11y 2.3.3.](https://github.com/ryersondmp/sa11y/releases/tag/2.3.3)
* Fixed undefined variable `$allowed_html`

### 1.0.8
* Tested with WordPress 6.0
* Upgrade to Sa11y 2.2.4
* Refactoring. Sa11y's main panel is created after the page has fully loaded to avoid conflicting JavaScript.

### 1.0.7
* Upgrade to Sa11y 2.2.3
* Sa11y's main panel is now 33% smaller.
* Added skip to previous issue shortcut: `alt + w` or `alt + <`

### 1.0.6
* Upgrade to Sa11y 2.2.2
* Added Polish, Ukrainian, and French (Canadian) translations. This does not include the "Advanced Settings" page at this time. The language will automatically change based on the page language.
* Name change: Ryerson University is now Toronto Metropolitan University!

### 1.0.5
* Minor fixes.

### 1.0.4
* Upgrade to Sa11y 2.1.9

### 1.0.3
* Upgrade to Sa11y 2.1.8

### 1.0.2
* Configured Sa11y's `doNotRun` prop.

### 1.0.1
* Bug fix.

### 1.0.0
* Initial release