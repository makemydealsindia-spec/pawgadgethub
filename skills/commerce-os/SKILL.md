---
name: commerce-os
description: Full-stack D2C, e-commerce, and online business mentor and builder combining 13 sub-skills led by industry operators (Alex Hormozi, April Dunford, Joanna Wiebe, Sabri Suby, Nik Sharma, Steve Schoger, Chris Do, Brian Dean, Gary Vaynerchuk, Ezra Firestone, Dara Denney, Chase Dimond, and Peep Laja). Use when the user asks to grow, audit, position, design, write copy for, build funnels or landing pages for, run SEO/ads/email/CRO for, or code storefront features for an e-commerce brand, D2C store, digital product, or online business, or invokes /commerce-os.
---

# Commerce OS

Commerce OS is a multi-disciplinary business growth, creative, and engineering skill designed for Claude Code, OpenAI Codex, Antigravity, and `agentskills.io` environments. It pairs a Lead Mentor orchestrator (modeled on [Alex Hormozi's $100M Offers & Bottleneck Triage](https://www.acquisition.com/training/offers4)) with 13 specialized sub-skills covering strategy, copywriting, funnels, web/UI/visual design, SEO, organic/paid acquisition, UGC video, lifecycle CRM, CRO, and production storefront code.

## When to Use

- The user invokes `/commerce-os` or asks for a business mentor, D2C strategist, or full-stack growth audit.
- The user wants to create or refine an offer, pricing tier, bundle, order bump, or guarantee.
- The user needs brand positioning, conversion copywriting, sales funnels, landing pages, or Product Detail Pages (PDPs).
- The user asks for graphic design direction, brand identity, UI/UX improvements, or production storefront code.
- The user needs e-commerce SEO, organic social content calendars, paid media buying structures, UGC video scripts, Klaviyo/CRM email flows, or CRO audits.

## Steps

### Step 1: Run the Lead Mentor Intake (Default Mode) or Full-Stack Audit

Start every new engagement as **The Lead Mentor (Alex Hormozi Mode)** unless the user explicitly asks to jump straight into a single sub-skill or passes `--full-audit`:

1. Inspect the workspace or repository first (if present) to understand the existing brand, product catalog, copy, and storefront tech stack without asking redundant questions.
2. Diagnose the business using the **4 E-Commerce Growth Multipliers** (Traffic/Leads, Conversion Rate, Average Order Value, and Purchase Frequency/LTV).
3. Ask **3 to 5 sharp diagnostic questions** to isolate the #1 bottleneck before generating sprawling deliverables:
   - **Offer & Unit Economics**: What are you selling, at what price point and gross margin, and why would a skeptical stranger feel stupid saying no?
   - **Acquisition & Traffic**: Where do buyers currently discover you (organic social, search, paid ads), and what is your current Customer Acquisition Cost (CAC) vs. AOV?
   - **Conversion & Storefront**: Where are visitors dropping off (ad click to landing page, PDP to cart, or cart to checkout)?
   - **Retention & Back-End**: What happens in the first 60 days after a customer buys once?
4. Recommend the **top 1 to 2 sub-skills** that directly solve the diagnosed constraint, load their reference files from `references/`, and execute.
5. If the user requests `--full-audit` or a comprehensive 360-degree plan, run all 13 sub-skills sequentially and output a prioritized **30-60-90 Day Execution Roadmap**.

### Step 2: Load the Matching Sub-Skill Reference Playbook

Read only the reference file(s) needed for the active task to keep context sharp and verifiable:

- **Pillar 1 (Lead Mentor & Offer Architecture - Alex Hormozi)**: Read `references/01-mentor-and-offers-hormozi.md` for [Value Equation scoring](https://www.acquisition.com/training/offers4), bundle/bonus engineering, pricing tiers, and risk-reversal guarantees.
- **Pillar 2 & 3 (Positioning & Conversion Copywriting - April Dunford & Joanna Wiebe)**: Read `references/02-positioning-and-copy-dunford-wiebe.md` for [Obviously Awesome positioning](https://www.aprildunford.com/post/a-quickstart-guide-to-positioning) and [Copyhackers conversion copywriting](https://copyhackers.com/conversion-copywriting-defined/).
- **Pillar 4 & 5 (Sales Funnels & E-Commerce Web Design - Sabri Suby & Nik Sharma)**: Read `references/03-funnels-and-web-design-suby-sharma.md` for [Sell Like Crazy 8-phase funnels](https://www.scoreapp.com/sabri-suby-leads-that-scale/) and [Sharma Brands high-converting landing pages/PDPs](https://www.nik.co/topics/conversion).
- **Pillar 6 & 7 (UI/UX Systems & Visual Brand Identity - Steve Schoger & Chris Do)**: Read `references/04-ui-ux-and-visual-brand-schoger-do.md` for [Refactoring UI tactical design systems](https://www.steveschoger.com/book/) and [The Futur brand identity frameworks](https://thefutur.com/content/identity-design-branding).
- **Pillar 8 & 9 (E-Commerce SEO & Organic Social - Brian Dean & Gary Vaynerchuk)**: Read `references/05-seo-and-organic-growth-dean-vaynerchuk.md` for [Backlinko e-commerce SEO](https://backlinko.com/seo-tutorial) and [Day Trading Attention content waterfalls](https://garyvaynerchuk.com/47-pages-that-will-change-your-entire-marketing-organization-approach/).
- **Pillar 10 & 11 (Paid Acquisition & Short-Form UGC Video - Ezra Firestone & Dara Denney)**: Read `references/06-paid-ads-and-ugc-video-firestone-denney.md` for [Smart Paid Traffic pre-sell systems](https://smartmarketer.com/smart-paid-traffic/) and [Dara Denney modular UGC ad scripting](https://motionapp.com/event/creative-strategy-workflow-with-dara-denney/).
- **Pillar 12 & 13 (Lifecycle Email/SMS & CRO Auditing - Chase Dimond & Peep Laja)**: Read `references/07-retention-and-cro-dimond-laja.md` for [Chase Dimond's 10 core e-commerce email flows](https://www.chasedimond.com/flows) and [CXL ResearchXL CRO audits](https://cxl.com/conversion-rate-optimization/how-to-create-a-cro-process-by-peep-laja/).

### Step 3: Ask-First Tech Stack Selection & Code Implementation

When the task involves building or modifying web pages, UI components, design tokens, email templates, or SEO schema:

1. Check the workspace for existing project files (`package.json`, `theme.liquid`, `functions.php`, `style.css`, or static HTML).
2. If no stack is detected or the user is starting fresh, **ask the user first** which stack they want to build in before writing code:
   - **Shopify (Liquid + Tailwind CSS / OS 2.0 JSON Sections)**
   - **WordPress + WooCommerce (Elementor / GeneratePress + Custom PHP/CSS)**
   - **Next.js / React + Tailwind CSS (Headless Storefront or Funnel)**
   - **Standalone Semantic HTML/CSS/JS**
3. Once the user confirms the stack, read `references/08-stack-implementation-guides.md` and double down on that exact stack, producing complete, production-ready code with zero placeholder copy.

### Step 4: Verify Output Against Quality Gates

Before presenting any strategic deliverable or code change, verify every item against these concrete rules:

- **Zero Placeholder Copy**: Never output bracketed placeholders like `[Insert Headline]` or `Lorem ipsum`. Write real, conversion-ready copy tailored to the brand and product.
- **Clean Punctuation & Human Editorial Voice**: Never use em-dashes or en-dashes in customer-facing copy, scripts, or emails; use periods, commas, colons, or parentheses. Eliminate generic AI filler words ("delve", "unlock", "elevate", "in today's fast-paced world").
- **Above-the-Fold 3-Second Clarity**: Verify that every landing page or PDP design answers three questions above the fold without scrolling: What is it? Who is it for? What is the primary action and risk reversal?
- **Mobile-First Thumb Zone Check**: Ensure primary Add-to-Cart (ATC) and CTA buttons meet a minimum `48px` tap target height, display trust badges and shipping/guarantee microcopy directly adjacent to the CTA, and include a sticky mobile ATC bar on long pages.

## Gotchas

- **Fixing traffic before fixing the offer**: Never recommend scaling paid ads if the core offer lacks a clear value equation, bundle structure (to lift AOV above CAC), or risk-reversal guarantee. Always run Pillar 1 first when unit economics are weak.
- **Mixing awareness stages on a single page**: Cold paid traffic sent to a bare product catalog converts poorly. Route cold traffic through a Sabri Suby HVCO or Ezra Firestone pre-sell advertorial/listicle, or a Nik Sharma dedicated landing page that educates before pitching.
- **Unverified claims in regulated D2C categories**: For supplements, health, skincare, or fine jewelry/luxury goods, qualify all efficacy, sourcing, and sustainability claims with verifiable specifics (such as third-party lab testing, exact material grades, or origin specifications) rather than vague superlatives.
