=== PawGadgetHub ===
Contributors: pawgadgethub
Requires at least: 6.3
Tested up to: 6.8
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A fast, lightweight review-site theme for smart pet technology and affiliate publishers.

== Description ==

* Navy / Teal / Tangerine design with Plus Jakarta Sans (headings) and Inter (body), self-hosted.
* No page builder, no jQuery, no external font or script requests.
* Homepage: hero, five category cards, featured reviews, trust pillars, newsletter box.
* Review tools: product boxes, comparison tables, pros and cons, buy buttons, automatic table of contents, affiliate disclosure banner.
* Affiliate links to known domains automatically get rel="sponsored nofollow noopener" and open in a new tab.
* Block patterns for a single review and a best-of roundup (Add block > Patterns > PawGadgetHub).

== Shortcodes ==

Product box:
[pgh_product title="Name" url="https://..." image="123" badge="Top Pick" rank="1" rating="4.8" price="$99" pros="One|Two" cons="One|Two" button="Check Price on Amazon"]Short summary.[/pgh_product]

Comparison table (one row per line, start a line with * to highlight the top pick, write Text::URL for a button):
[pgh_compare headers="Product|Best for|Price|Rating|Buy"]
*Top pick|Most people|$99|4.8|Check Price::https://...
Runner-up|Budget|$59|4.4|Check Price::https://...
[/pgh_compare]

Pros and cons:  [pgh_proscons pros="One|Two" cons="One|Two"]
Buy button:      [pgh_cta url="https://..."]Check Price on Amazon[/pgh_cta]

== Credits ==

Plus Jakarta Sans (c) The Plus Jakarta Sans Project Authors, SIL Open Font License 1.1.
Inter (c) The Inter Project Authors, SIL Open Font License 1.1.

== Changelog ==

= 1.0.0 =
* Initial release.
