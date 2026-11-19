# Pillars 12 & 13: Email/SMS Retention (Chase Dimond) & CRO Auditing (Peep Laja)

Reference frameworks modeled on **Chase Dimond** ([Ecommerce Email Automation Flows](https://www.chasedimond.com/flows)) and **Peep Laja** ([CXL ResearchXL CRO Process](https://cxl.com/conversion-rate-optimization/how-to-create-a-cro-process-by-peep-laja/)).

## Pillar 12: Chase Dimond's E-Commerce Email & SMS Lifecycle Blueprint

As documented in [Chase Dimond's automation blueprint](https://www.chasedimond.com/flows), automated flows represent roughly 2% of email sends yet generate 30%+ of total email revenue.

### The 8 Mandatory E-Commerce Automated Flows

Whenever building or auditing lifecycle retention, structure these core flows with clear triggers and suppression rules:

1. **Welcome Series (4 Emails over 7 Days)**:
   - *Trigger*: Newsletter or pop-up/quiz opt-in (suppress once first purchase occurs).
   - *Sequence*: Email 1 (Immediate welcome gift/HVCO delivery + top sellers) -> Email 2 (Founder story + unique brand standard) -> Email 3 (Social proof wall + FAQ handling) -> Email 4 (Final incentive reminder + risk-reversal guarantee).
2. **Site Abandonment Flow (1-2 Emails)**:
   - *Trigger*: Known subscriber visits site without viewing a specific product.
   - *Sequence*: Helpful category guide and bestseller recommendations.
3. **Browse Abandonment Flow (2 Emails)**:
   - *Trigger*: Viewed a Product Detail Page (PDP) but did not add to cart (wait 2 hours).
   - *Sequence*: Email 1 ("Did something catch your eye?" + dynamic product block + customer reviews) -> Email 2 (Comparison chart + guarantee).
4. **Abandoned Cart vs. Abandoned Checkout Flows (3-4 Emails + 1-2 SMS)**:
   - *Trigger*: Added to cart or initiated checkout without completing purchase.
   - *Sequence*: 1 hour (Helpful reminder + direct cart link + support reply prompt) -> 24 hours (Overcome top objections + testimonials) -> 48 hours (Guarantee/bundle value or tiered incentive) -> 72 hours (Plain-text note from founder asking what held them back).
5. **Post-Purchase Onboarding & Cross-Sell Flow (4 Emails)**:
   - *Trigger*: Order fulfilled (split by First-Time vs. Repeat Buyer).
   - *Sequence*: Order excitement + how to get the best result on Day 1 -> Care/usage tips -> Review/UGC request -> Natural cross-sell or replenishment offer.
6. **Replenishment / Subscription Reminder Flow (2 Emails)**:
   - *Trigger*: Expected depletion window for consumable products (such as Day 25 for a 30-day supply).
7. **Customer Winback Flow (3 Emails at 60/90/120 Days)**:
   - *Trigger*: Lapsed one-time buyer who has not reordered within expected window.
8. **VIP & Sunset Flows**:
   - Reward top 5% LTV customers with early access; clean unengaged 120-day+ profiles to protect inbox deliverability.

## Pillar 13: Peep Laja's ResearchXL Conversion Rate Optimization (CRO) System

Follow [Peep Laja's CXL ResearchXL framework](https://cxl.com/conversion-rate-optimization/how-to-create-a-cro-process-by-peep-laja/) to diagnose conversion leaks with evidence rather than guesswork:

### 1. The 6-Step ResearchXL Diagnostic Audit

When reviewing a live URL, screenshot, or storefront codebase, score each page across five heuristic dimensions:

1. **Clarity (Score 1-5)**: Does the visitor understand what the product is, who it is for, and what it costs within 3 seconds?
2. **Relevancy & Scent Trail (Score 1-5)**: Does the landing page headline and visual match the exact promise of the ad, search snippet, or social link that brought them here?
3. **Value & Incentive to Act (Score 1-5)**: Is the bundle savings, bonus, or unique benefit compelling enough to act right now?
4. **Friction & Cognitive Load (Score 1-5)**: Are there long paragraphs, hidden shipping fees, confusing variant selectors, slow image loads, or multi-step form fields blocking checkout?
5. **Distraction (Score 1-5)**: Are competing pop-ups, auto-playing carousels, or header navigation links pulling buyers away from the primary CTA?

### 2. PXL Prioritization & Fix Execution

Rank every discovered issue into a prioritized **CRO Fix Table** (`Issue | Page Location | Revenue Impact | Implementation Effort | Exact Code/Copy Fix`) and implement the top quick wins directly in the codebase once approved.
