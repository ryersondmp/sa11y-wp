=== Sa11y, the accessibility quality assurance assistant | Accessibility Checker ===
Contributors: adamchaboryk
Tags: accessibility, accessibility automated testing, accessibility checker, wcag, audit
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sa11y is an accessibility quality assurance tool that visually highlights common accessibility and usability issues. Geared towards content authors, Sa11y straightforwardly identifies errors or warnings at the source with a simple tooltip on how to fix them.

== Description ==

Sa11y is an accessibility quality assurance tool that visually highlights common accessibility and usability issues. Geared towards content authors, Sa11y straightforwardly identifies errors or warnings at the source with a simple tooltip on how to fix them.

Sa11y works in **Preview** mode.

= Features =

* Over 50 checks.
* Concise tooltips explain issues right at the source.
* Automatically checks content once the page has loaded.
* Highly customizable. Turn off or hide irrelevant checks.
* Content editors can temporarily dismiss warnings.
* 100% free and open source.
* Available in English, French, Spanish, Polish, Ukrainian, German, Swedish, and many more languages through machine translations.
* Supports Multisite: create global settings and custom defaults for all websites on your network.

Visit the [project website](https://sa11y.netlify.app/) for a demo or to learn more!

== Screenshots ==
1. Logged-in view with Sa11y showing heading hierarchy and non-descriptive link text errors.
2. The Images tab makes it easy to review all images and their corresponding alt text within a page for accuracy and quality. Warnings can also be temporarily dismissed.
3. The Settings tab has additional toggleable checks, including a dark mode option.
4. The Advanced Settings page where admins can customize different settings.

== Changelog ==

= 1.1.7 =
* Upgrade to Sa11y 3.2.1
* New: The Images tab makes it easy to review all images and their corresponding alt text within a page for accuracy and quality.
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

= 1.1.6 =
* Upgrade to Sa11y 3.0.8
* Minor bug fixes.
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

= 1.1.5 =
* Upgrade Sa11y 3.0.6
* New feature: Export all errors and warnings on a page as CSV or HTML. Make it easier to compile accessibility audits or export results into your favourite reporting tool. This feature is off by default. Please enable via Advanced Settings.
* New feature: More helpful preview of hidden issues. When using the Skip-to-Issue button, Sa11y will display more helpful previews of hidden issues. Images, iframes, video, and audio content will be displayed in its entirety, otherwise a preview of the issue’s HTML will be displayed. This will help make it easier to identify hidden or off-screen issues.
* New machine translations: Bulgarian, Hungarian, Korean, Slovak
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

= 1.1.4 =
* Upgrade Sa11y 3.0.6
* New feature: Export all errors and warnings on a page as CSV or HTML. Make it easier to compile accessibility audits or export results into your favourite reporting tool. Enable via Advanced Settings.
* New feature: More helpful preview of hidden issues. When using the Skip-to-Issue button, Sa11y will display more helpful previews of hidden issues. Images, iframes, video, and audio content will be displayed in its entirety, otherwise a preview of the issue’s HTML will be displayed. This will help make it easier to identify hidden or off-screen issues.
* New machine translations: Bulgarian, Hungarian, Korean, Slovak
* Check out the [latest release notes for Sa11y.](https://github.com/ryersondmp/sa11y/releases)

= 1.1.3 =
* Upgrade to Sa11y 3.0.3
* Added Spanish and several machine translations including: Czech, Danish, Greek, Estonian, Finnish, Indonesian, Italian, Japanese, Lithuanian, Latvian, Norwegian (Bokmål), Dutch, Portuguese (Brazil), Portuguese (Portugal), Romanian, Slovenian, Turkish, Chinese (Simplified)
* New ruleset to flag non-descript DOI (digital object identifier) links.
* Check out the [latest release notes for Sa11y 3.0.3!](https://github.com/ryersondmp/sa11y/releases/tag/3.0.3)

= 1.1.2 =
* Bug fixes.

= 1.1.1 =
* Upgrade to Sa11y 3!
* Content editors now have the ability to temporarily dismiss warnings, preview pages with various colour filters, and new admin settings.
* German translation added.
* Multisite support added. Multisite admins can create global exclusions and provide custom defaults.
* Check out the [latest release notes for Sa11y 3.0!](https://github.com/ryersondmp/sa11y/releases/tag/3.0.0)

= 1.1.0 =
* Improved Page Outline panel.
* Various bug fixes.
* Upgrade to Sa11y 2.3.5
* View [latest release notes for Sa11y](https://github.com/ryersondmp/sa11y/releases/)

= 1.0.9 =
* List of headings in the Page Outline panel are now clickable.
* Swedish translation added.
* View [full release notes for Sa11y 2.3.3.](https://github.com/ryersondmp/sa11y/releases/tag/2.3.3)

= 1.0.8 =
* Tested with WordPress 6.0
* Upgrade to Sa11y 2.2.4
* Refactoring. Sa11y's main panel is created after the page has fully loaded to avoid conflicting JavaScript.

= 1.0.7 =
* Upgrade to Sa11y 2.2.3
* Sa11y's main panel is now 33% smaller.
* Added skip to previous issue shortcut: alt + w or alt + <

= 1.0.6 =
* Upgrade to Sa11y 2.2.2
* Added Polish, Ukrainian, and French (Canadian) translations. This does not include the "Advanced Settings" page at this time. The language will automatically change based on the page language.
* Name change: Ryerson University is now Toronto Metropolitan University!

= 1.0.5 =
* Minor fixes.

= 1.0.4 =
* Upgrade to Sa11y 2.1.9

= 1.0.3 =
* Upgrade to Sa11y 2.1.8

= 1.0.2 =
* Bug fix.

= 1.0.1 =
* Bug fix.

= 1.0.0 =
* Initial release of official WordPress plugin.