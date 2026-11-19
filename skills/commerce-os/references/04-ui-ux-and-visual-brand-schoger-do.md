# Pillars 6 & 7: UI/UX Systems (Steve Schoger) & Graphic/Brand Design (Chris Do)

Reference frameworks modeled on **Steve Schoger** ([Refactoring UI](https://www.steveschoger.com/book/)) and **Chris Do** ([The Futur Identity Design & Branding](https://thefutur.com/content/identity-design-branding)).

## Pillar 6: Steve Schoger's Refactoring UI Tactical Rules

Apply [Steve Schoger's Refactoring UI principles](https://www.steveschoger.com/book/) whenever auditing interfaces or writing CSS/Tailwind code:

### 1. Visual Hierarchy Without Relying on Font Size Alone

- Do not make every important element huge. Use **font weight** (`600` or `700`) and **color contrast** (dark slate `#0F172A` for primary text, muted slate `#475569` for secondary labels, `#94A3B8` for tertiary metadata) to create hierarchy.
- De-emphasize competing actions so the primary conversion CTA ("Add to Cart" or "Complete Checkout") dominates the viewport:
  - **Primary CTA**: Solid high-contrast fill, bold text, subtle elevation shadow.
  - **Secondary Action**: Outline or soft neutral surface fill.
  - **Tertiary Action**: Plain text link with hover underline.

### 2. Spacing, Sizing & Layout Systems

- Start with **too much whitespace** and remove it only when elements need tighter grouping.
- Use a strict **non-linear spacing and sizing scale** (`4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px, 96px, 128px`) so adjacent steps are visually distinct.
- Constrain prose line width between **45 and 75 characters** (`max-w-prose` or `60ch-68ch`) so product descriptions and advertorials remain effortless to scan.
- Avoid ambiguous spacing: the gap *between* cards or form groups must always be noticeably larger than the gap *inside* each card or label-input pair.

### 3. Color, Borders & Depth

- Never use pure grey (`#808080`) on colored backgrounds; saturate greys with a subtle warm (stone/sand for luxury/wellness) or cool (slate/zinc for tech/modern) undertone.
- Use fewer borders: create separation with subtle background surface shifts (`#FFFFFF` card on `#F8FAFC` background) or soft two-layer box shadows instead of heavy `1px solid` lines everywhere.

## Pillar 7: Chris Do's Brand Identity & Graphic Design System

Follow [Chris Do's identity and brand strategy principles](https://thefutur.com/content/identity-design-branding) to make visual assets look intentional and memorable:

### 1. Strategic Brand Personality & Archetype Alignment

- Before choosing colors or fonts, define 3 **Brand Voice & Visual Polarities** (for example: *Clinical Precision vs. Earthy Botanical*, *Heritage Luxury vs. Streetwear Minimalist*).
- Ensure packaging, social carousels, ad creatives, and web layouts share a unified visual signature so a customer recognizes the brand with the logo covered.

### 2. Typography Pairing & Scale Contrast

- Pair at most **two typefaces**:
  - **Display / Headline Font**: High-character editorial serif (such as *Cormorant Garamond*, *Playfair Display*, *Instrument Serif*) or geometric grotesk (*Space Grotesk*, *Plus Jakarta Sans*).
  - **Body / UI Font**: Highly legible neo-grotesque sans-serif (*Inter*, *DM Sans*, *Plus Jakarta Sans*).
- Use **extreme scale contrast** in graphic layouts and ad creatives: pair a bold, oversized focal headline or stat with crisp, compact caption metadata and generous negative space.

### 3. Grid Systems & Ad Creative Layout Rules

- Align all graphic compositions to an explicit 4-column (mobile/social) or 12-column (web) grid.
- Enforce the **1-2-3 Focal Point Rule** on every static ad, carousel slide, or packaging face:
  1. **1st Read (From 10 feet / thumb-scroll speed)**: Single dominant visual or 3-to-5 word headline hook.
  2. **2nd Read**: Core value prop, proof badge, or before/after contrast.
  3. **3rd Read**: Brand mark, specification detail, or CTA badge.
