# Stack Implementation Playbooks (Ask-First Code Execution)

When moving from strategy and copy into code execution, always confirm the user's target stack first (if not already detected in the repository), then follow the stack-specific engineering rules below.

## 1. Shopify (OS 2.0 Liquid + Tailwind CSS / Scoped CSS)

- **Architecture**: Build modular, Merchant-Editable **OS 2.0 Sections** (`sections/*.liquid`) with complete `{% schema %}` JSON blocks so headlines, bundle tiers, badges, and FAQs can be customized in the Theme Editor while shipping with high-converting pre-filled defaults.
- **Conversion Components to Include**:
  - Tiered Bundle Radio Selector (`1-Pack`, `2-Pack Most Popular`, `3-Pack Best Value`) wired to Shopify Ajax Cart API (`/cart/add.js`).
  - Dynamic Free-Shipping Progress Bar in the slide-out cart drawer.
  - Sticky Mobile Add-to-Cart bar that appears once the primary hero CTA scrolls out of view (`IntersectionObserver`).
  - Automatic `JSON-LD` Product and FAQ structured data injection.

## 2. WordPress + WooCommerce (Elementor / GeneratePress + Custom PHP/CSS)

- **Architecture**: Write clean, lightweight **GeneratePress / Elementor-compatible PHP snippets** (`functions.php` or custom plugin module) and scoped, zero-bloat CSS (`style.css`).
- **Conversion Components to Include**:
  - Custom WooCommerce single-product hooks (`woocommerce_single_product_summary`) for star rating proof badges, icon benefit bullets, tiered quantity breaks, and trust microcopy directly below the `single_add_to_cart_button`.
  - Checkout friction reducer removing unnecessary billing fields and adding an in-checkout Order Bump checkbox (`woocommerce_review_order_before_submit`).
  - Rank Math / custom `wp_head` JSON-LD schema and Core Web Vitals optimization (lazy-loading below-the-fold assets, preloading hero LCP image).

## 3. Next.js / React + Tailwind CSS (Headless Storefront, Funnel, or Digital Product Hub)

- **Architecture**: Write typed, accessible React components using **Steve Schoger's Refactoring UI spacing and color tokens** in Tailwind CSS.
- **Conversion Components to Include**:
  - Hero section with 3-second clarity, interactive bundle state selector, social proof strip, "Us vs. Alternatives" comparison table, interactive ingredient/feature tabs, and sticky mobile CTA bar.
  - Built-in Next.js Metadata API (`generateMetadata`) and structured `application/ld+json` script tags for SEO and AI visibility.

## 4. Pre-Delivery Code Verification Checklist

Run these checks before presenting any generated storefront or funnel code:

- [ ] **No placeholder text**: Every heading, bullet, button, review, and FAQ contains complete, brand-specific conversion copy.
- [ ] **No em-dashes or en-dashes**: All customer-facing strings use clean punctuation.
- [ ] **Mobile responsiveness**: Layout stacks cleanly at `375px` viewport width with `>=48px` tap targets on all interactive controls.
- [ ] **Valid syntax**: HTML tags, Liquid blocks, PHP hooks, or JSX components close cleanly with zero syntax errors.
