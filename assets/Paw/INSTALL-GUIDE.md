# PawGadgetHub Theme: Install & Setup Guide

## 1. Install the theme (5 minutes)

1. Log in at `pawgadgethub.com/wp-admin`.
2. Go to **Appearance > Themes > Add New > Upload Theme**.
3. Choose **`pawgadgethub.zip`**, click **Install Now**, then **Activate**.
4. Go to **Settings > Permalinks**, choose **Post name**, and save.

## 2. First-time setup checklist

| Step | Where | What to do |
|---|---|---|
| Logo | Appearance > Customize > Site Identity | The paw and wordmark are built in. Only upload a logo if you want to replace them. |
| Site icon (favicon) | Same screen | Upload `brand/site-icon-512.png` |
| Categories | Posts > Categories | Make sure these 5 slugs exist: `smart-feeders`, `gps-trackers`, `pet-cameras`, `litter-boxes`, `water-fountains`. The homepage cards and menu use them. |
| Menu | Appearance > Menus | Optional. With no menu set, the theme shows the 5 categories automatically. |
| Legal pages | Pages > Add New | Create `about`, `contact`, `affiliate-disclosure`, `privacy-policy`, `terms` (use these exact slugs). The footer and disclosure banner link to them automatically. |
| Privacy Policy | Pages | A new WordPress site already has a **draft** "Privacy Policy" page using the slug `privacy-policy`. Edit and publish that one (or delete it first), so your link doesn't become `privacy-policy-2`. |
| Newsletter | Customize > PawGadgetHub > Newsletter | Paste the form action URL from Mailchimp, ConvertKit, MailerLite or Brevo, or enter a plugin shortcode. The box stays hidden from visitors until this is set. |
| Amazon statement | Customize > PawGadgetHub > Affiliate & Disclosure | Pre-filled with the official Amazon Associates wording. Keep it. |
| Homepage | Settings > Reading | Leave on "Your latest posts" (or pick a static page; the theme's homepage design is used either way). |
| Featured section | Posts > Tags | Tag any post **featured** to pin it to the homepage. Otherwise the 4 newest posts show. |

## 3. Writing reviews fast

In the post editor, click **+ > Patterns > PawGadgetHub** and insert **Single product review** or **Best-of roundup**. Then replace the sample text and links.

**Product box**
```
[pgh_product title="Petlibro Granary Feeder" url="https://www.amazon.com/dp/XXXX?tag=YOURTAG-20" badge="Top Pick" rank="1" rating="4.8" price="$99" pros="Reliable app|Easy to clean" cons="Loud hopper"]Why it is our pick.[/pgh_product]
```
Add `image="123"` (media library ID) or an image URL. Without one, a paw placeholder shows.

**Comparison table** (one row per line, `*` marks the top pick, `Text::URL` makes a button)
```
[pgh_compare headers="Product|Best for|Price|Rating|Buy"]
*Petlibro Granary|Most people|$99|4.8|Check Price::https://...
Budget Feeder X|Budget|$59|4.4|Check Price::https://...
[/pgh_compare]
```

**Also available:** `[pgh_proscons pros="a|b" cons="a|b"]`, `[pgh_cta url="..."]Button text[/pgh_cta]`, and block styles under **Group** (Callout box, Quick verdict), **Table** (Comparison table) and **Button** (Buy button).

## 4. What happens automatically

* **Table of contents** on posts with 3+ H2/H3 headings (turn off per post in the "PawGadgetHub options" box, or globally in the Customizer).
* **Affiliate disclosure banner** at the top of every post (turn off per post only when it has no affiliate links).
* **Affiliate link attributes**: links to Amazon, Tractive, Furbo, Petlibro, Whisker, Chewy and a few networks get `rel="sponsored nofollow noopener"` and open in a new tab.
* **Fonts** load from the theme itself, with no Google Fonts requests.

## 5. Recommended plugins

* **Rank Math** (free) for SEO and schema. The theme avoids duplicating its tags.
* **A contact form plugin** (WPForms Lite or Contact Form 7) for the Contact page.
* **A caching plugin** (LiteSpeed Cache on Hostinger) plus **Cloudflare** as in your blueprint.

## 6. Files in this folder

* `pawgadgethub.zip`: the installable theme.
* `brand/`: logo files (SVG and PNG, full colour and reversed), site icon, social share image.
* `pawgadgethub/`: the unzipped theme source.
