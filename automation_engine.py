#!/usr/bin/env python3
"""
PawGadgetHub.com Automation Engine
----------------------------------
Fully automated SEO pipeline:
1. Keyword selection from validated pool
2. AI-powered long-form content generation (Gemini API) tailored for US pet market
3. Royalty-free HD image retrieval & upload (Pexels API -> WordPress Media)
4. WordPress publishing via REST API (Featured image, tags, categories, meta)
5. Instant search indexing ping (IndexNow for Bing/Yandex)
"""

import os
import sys
import json
import base64
import argparse
import urllib.request
import urllib.error
import urllib.parse
from datetime import datetime

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
CONFIG_PATH = os.path.join(SCRIPT_DIR, "config.json")
KEYWORDS_PATH = os.path.join(SCRIPT_DIR, "keywords.json")

def load_config():
    config = {
        "site_url": "https://pawgadgethub.com",
        "wp_user": "admin",
        "wp_app_password": "",
        "gemini_api_key": "",
        "pexels_api_key": "",
        "amazon_tag": "pawgadgethub-20"
    }
    local_config_path = os.path.join(SCRIPT_DIR, "config.local.json")
    if os.path.exists(CONFIG_PATH):
        with open(CONFIG_PATH, "r", encoding="utf-8") as f:
            config.update(json.load(f))
    if os.path.exists(local_config_path):
        with open(local_config_path, "r", encoding="utf-8") as f:
            config.update(json.load(f))
    # Check environment variables overrides
    if os.environ.get("GEMINI_API_KEY"):
        config["gemini_api_key"] = os.environ.get("GEMINI_API_KEY")
    if os.environ.get("PEXELS_API_KEY"):
        config["pexels_api_key"] = os.environ.get("PEXELS_API_KEY")
    if os.environ.get("WP_APP_PASSWORD"):
        config["wp_app_password"] = os.environ.get("WP_APP_PASSWORD")
    if os.environ.get("AMAZON_TAG"):
        config["amazon_tag"] = os.environ.get("AMAZON_TAG")
    return config

def save_config(config):
    with open(CONFIG_PATH, "w", encoding="utf-8") as f:
        json.dump(config, f, indent=2)

def load_keywords():
    if not os.path.exists(KEYWORDS_PATH):
        raise FileNotFoundError(f"Keywords file not found: {KEYWORDS_PATH}")
    with open(KEYWORDS_PATH, "r", encoding="utf-8") as f:
        return json.load(f)

def save_keywords(keywords):
    with open(KEYWORDS_PATH, "w", encoding="utf-8") as f:
        json.dump(keywords, f, indent=2)

def get_wp_auth_header(config):
    user = config["wp_user"]
    pwd = config["wp_app_password"].replace(" ", "")
    token = base64.b64encode(f"{user}:{pwd}".encode()).decode()
    return f"Basic {token}"

def get_published_posts(config):
    """Fetches all currently live published posts from WordPress for internal linking."""
    wp_posts_url = f"{config['site_url'].rstrip('/')}/wp-json/wp/v2/posts?status=publish&per_page=50"
    headers = {"Authorization": get_wp_auth_header(config)}
    req = urllib.request.Request(wp_posts_url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            posts = json.loads(resp.read().decode("utf-8"))
            return [
                {
                    "id": p["id"],
                    "title": p["title"]["rendered"],
                    "link": p["link"],
                    "categories": p.get("categories", [])
                }
                for p in posts
            ]
    except Exception as e:
        print(f"[Warning] Could not fetch published posts for internal linking: {e}")
        return []

def inject_bidirectional_backlink(new_post_id, new_post_title, new_post_url, category_id, config, live_posts):
    """Finds an existing post in the same category and injects a contextual related reading link."""
    # Find a sibling post in the same category that is not the new post
    target_post = None
    for p in live_posts:
        if p["id"] != new_post_id and category_id in p.get("categories", []):
            target_post = p
            break
            
    if not target_post:
        return
        
    print(f"[Internal Linking] Injecting contextual backlink into post #{target_post['id']} (\"{target_post['title']}\")...")
    
    # Fetch target post full content
    headers = {
        "Authorization": get_wp_auth_header(config),
        "Content-Type": "application/json"
    }
    try:
        get_req = urllib.request.Request(
            f"{config['site_url'].rstrip('/')}/wp-json/wp/v2/posts/{target_post['id']}",
            headers=headers
        )
        with urllib.request.urlopen(get_req, timeout=15) as resp:
            post_data = json.loads(resp.read().decode("utf-8"))
            existing_content = post_data["content"]["rendered"]
            
        # Avoid duplicate links
        if new_post_url in existing_content:
            return
            
        related_box = f'''
<div style="background:#f8fafc;border-left:4px solid #3b82f6;padding:14px 18px;margin:28px 0;border-radius:6px;">
  <strong>Related Guide:</strong> Also read our in-depth review on <a href="{new_post_url}" style="color:#1d4ed8;font-weight:600;">{new_post_title}</a> for more expert testing insights.
</div>
'''
        # Append before the final heading or at the end
        if "<h2>Frequently Asked Questions" in existing_content:
            updated_content = existing_content.replace(
                "<h2>Frequently Asked Questions",
                related_box + "\n<h2>Frequently Asked Questions"
            )
        else:
            updated_content = existing_content + related_box
            
        update_req = urllib.request.Request(
            f"{config['site_url'].rstrip('/')}/wp-json/wp/v2/posts/{target_post['id']}",
            data=json.dumps({"content": updated_content}).encode("utf-8"),
            headers=headers,
            method="POST"
        )
        with urllib.request.urlopen(update_req, timeout=15) as up_resp:
            print(f"[Internal Linking] Successfully backlinked from Post #{target_post['id']} -> {new_post_url}!")
    except Exception as e:
        print(f"[Warning] Could not update backlink on post #{target_post['id']}: {e}")

def call_gemini(prompt, api_key, max_retries=4):
    """Calls Gemini API using standard library urllib with retry/backoff and fallback models."""
    models = [
        "gemini-3.1-flash-lite",
        "gemini-2.5-flash",
        "gemini-2.5-flash-lite"
    ]
    payload = {
        "contents": [
            {
                "parts": [
                    {"text": prompt}
                ]
            }
        ],
        "generationConfig": {
            "temperature": 0.7,
            "maxOutputTokens": 8192,
            "responseMimeType": "application/json"
        }
    }
    
    import time
    last_error = None
    for model in models:
        url = f"https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent?key={api_key}"
        for attempt in range(max_retries):
            req = urllib.request.Request(
                url,
                data=json.dumps(payload).encode("utf-8"),
                headers={"Content-Type": "application/json"},
                method="POST"
            )
            try:
                with urllib.request.urlopen(req, timeout=90) as response:
                    res_json = json.loads(response.read().decode("utf-8"))
                    candidate = res_json["candidates"][0]["content"]["parts"][0]["text"]
                    return json.loads(candidate)
            except urllib.error.HTTPError as e:
                error_body = e.read().decode("utf-8")
                last_error = f"Gemini API Error {e.code} ({model}): {error_body}"
                if e.code in (429, 500, 502, 503, 504) and attempt < max_retries - 1:
                    wait_time = (attempt + 1) * 15
                    print(f"[Retryable Error {e.code}] {model} busy. Waiting {wait_time}s before retrying...")
                    time.sleep(wait_time)
                else:
                    print(f"[Fallback] {model} failed ({e.code}), trying next fallback model if available...")
                    break
            except Exception as e:
                last_error = f"Gemini Request Error ({model}): {e}"
                if attempt < max_retries - 1:
                    wait_time = (attempt + 1) * 15
                    time.sleep(wait_time)
                else:
                    break
    raise RuntimeError(last_error)

# High-resolution Unsplash royalty-free photos categorized for pet tech (Zero API keys needed!)
CATEGORY_IMAGE_POOL = {
    "Smart Feeders": [
        "https://images.unsplash.com/photo-1543852786-1cf6624b9987?w=1200&q=80",
        "https://images.unsplash.com/photo-1574158622682-e40e69881006?w=1200&q=80",
        "https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=1200&q=80"
    ],
    "GPS & Trackers": [
        "https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=1200&q=80",
        "https://images.unsplash.com/photo-1552053831-71594a27632d?w=1200&q=80",
        "https://images.unsplash.com/photo-1537151608828-ea2b11777ee8?w=1200&q=80"
    ],
    "Pet Cameras": [
        "https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=1200&q=80",
        "https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=1200&q=80",
        "https://images.unsplash.com/photo-1561037404-61cd46aa615b?w=1200&q=80"
    ],
    "Self-Cleaning Litter Boxes": [
        "https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=1200&q=80",
        "https://images.unsplash.com/photo-1573865526739-10659fec78a5?w=1200&q=80",
        "https://images.unsplash.com/photo-1495360010541-f48722b34f7d?w=1200&q=80"
    ],
    "Smart Water Fountains": [
        "https://images.unsplash.com/photo-1533738363-b7f9aef128ce?w=1200&q=80",
        "https://images.unsplash.com/photo-1518791841217-8f162f1e1131?w=1200&q=80",
        "https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=1200&q=80"
    ]
}

def fetch_royalty_free_image(category, query):
    """Fetches high-res royalty-free pet photo without requiring external API keys."""
    import random
    pool = CATEGORY_IMAGE_POOL.get(category, CATEGORY_IMAGE_POOL["Smart Feeders"])
    chosen_url = random.choice(pool)
    return {"url": chosen_url, "credit": "Unsplash", "alt": query}

def upload_image_to_wordpress(image_info, config):
    """Downloads image and uploads to WordPress Media Library via REST API."""
    if not image_info:
        return None
        
    try:
        # Download image bytes
        req = urllib.request.Request(image_info["url"], headers={"User-Agent": "Mozilla/5.0"})
        with urllib.request.urlopen(req, timeout=30) as resp:
            img_bytes = resp.read()
            
        filename = f"{image_info['alt'].replace(' ', '-').lower()[:40]}.jpg"
        wp_media_url = f"{config['site_url'].rstrip('/')}/wp-json/wp/v2/media"
        
        headers = {
            "Authorization": get_wp_auth_header(config),
            "Content-Type": "image/jpeg",
            "Content-Disposition": f'attachment; filename="{filename}"'
        }
        
        up_req = urllib.request.Request(wp_media_url, data=img_bytes, headers=headers, method="POST")
        with urllib.request.urlopen(up_req, timeout=30) as up_resp:
            media_data = json.loads(up_resp.read().decode("utf-8"))
            print(f"[Success] Uploaded featured image to WP Media: ID {media_data['id']}")
            return media_data["id"]
    except Exception as e:
        print(f"[Warning] Failed to upload image to WordPress: {e}")
        return None

def publish_to_wordpress(post_data, config, category_id, featured_media_id=None, status="publish"):
    """Publishes a post via WordPress REST API."""
    wp_posts_url = f"{config['site_url'].rstrip('/')}/wp-json/wp/v2/posts"
    
    payload = {
        "title": post_data["title"],
        "content": post_data["content_html"],
        "status": status,
        "categories": [category_id],
        "excerpt": post_data["meta_description"],
        "slug": post_data["slug"]
    }
    
    if featured_media_id:
        payload["featured_media"] = featured_media_id
        
    headers = {
        "Authorization": get_wp_auth_header(config),
        "Content-Type": "application/json"
    }
    
    req = urllib.request.Request(
        wp_posts_url,
        data=json.dumps(payload).encode("utf-8"),
        headers=headers,
        method="POST"
    )
    
    with urllib.request.urlopen(req, timeout=30) as resp:
        return json.loads(resp.read().decode("utf-8"))

def update_rank_math_meta(post_id, focus_keyword, seo_title, meta_description, config):
    """Sets Rank Math SEO focus keyword, title, and meta description via REST API."""
    try:
        url = f"{config['site_url'].rstrip('/')}/wp-json/rankmath/v1/updateMeta"
        payload = {
            "objectID": post_id,
            "objectType": "post",
            "meta": {
                "rank_math_focus_keyword": focus_keyword,
                "rank_math_title": seo_title,
                "rank_math_description": meta_description
            }
        }
        headers = {
            "Authorization": get_wp_auth_header(config),
            "Content-Type": "application/json",
            "User-Agent": "PawGadgetHub-Automation/2.0"
        }
        req = urllib.request.Request(url, data=json.dumps(payload).encode("utf-8"), headers=headers, method="POST")
        with urllib.request.urlopen(req, timeout=15) as resp:
            print(f"[SEO] Rank Math metadata updated for Post #{post_id} (Focus: '{focus_keyword}')")
    except Exception as e:
        print(f"[Warning] Could not update Rank Math metadata: {e}")

def ping_indexnow(site_url, post_url, config=None):
    """Submits URL via WordPress Rank Math Instant Indexing (IndexNow) endpoint."""
    try:
        if config:
            url = f"{site_url.rstrip('/')}/wp-json/rankmath/v1/in/submitUrls"
            payload = {"urls": post_url, "action": "bing_submit"}
            headers = {
                "Authorization": get_wp_auth_header(config),
                "Content-Type": "application/json",
                "User-Agent": "PawGadgetHub-Automation/2.0"
            }
            req = urllib.request.Request(url, data=json.dumps(payload).encode("utf-8"), headers=headers, method="POST")
            with urllib.request.urlopen(req, timeout=15) as resp:
                print(f"[Instant Indexing] Submitted {post_url} via Rank Math IndexNow ({resp.read().decode()})")
                return
    except Exception as e:
        print(f"[Notice] Rank Math Instant Indexing skipped: {e}")

def build_prompt_for_keyword(item, amazon_tag, published_posts, config):
    keyword = item["keyword"]
    category = item["category"]
    post_type = item.get("post_type", "roundup")
    
    # Format internal links reference
    internal_options = []
    # Add parent category hub
    slug_map = {
        "Smart Feeders": "smart-feeders",
        "GPS & Trackers": "gps-trackers",
        "Pet Cameras": "pet-cameras",
        "Self-Cleaning Litter Boxes": "litter-boxes",
        "Smart Water Fountains": "water-fountains"
    }
    cat_slug = slug_map.get(category, "smart-feeders")
    internal_options.append(f"- Parent Category Hub: \"{category} Reviews & Guides\" -> {config['site_url'].rstrip('/')}/category/{cat_slug}/")
    
    for p in published_posts[:6]:
        internal_options.append(f"- Published Article: \"{p['title']}\" -> {p['link']}")
        
    internal_links_text = "\n".join(internal_options) if internal_options else "None yet (link to parent category)."

    reviewed_products_text = ""
    if "reviewed_products" in item:
        prods_list = []
        for p in item["reviewed_products"]:
            aff_url = p.get('affiliate_url', f"https://www.amazon.com/dp/{p['asin']}?tag={amazon_tag}")
            img_url = p.get('image_url', '')
            img_note = f", Product Image URL: {img_url}" if img_url else ""
            prods_list.append(f"- Product: {p['name']} (ASIN: {p['asin']}, Best For: {p['best_for']}, Approx. MSRP: {p['msrp']}, Target Affiliate URL: {aff_url}{img_note})")
        reviewed_products_text = "EXACT PRE-VETTED PRODUCTS TO REVIEW (USE THESE EXACT COMMERCIALLY AVAILABLE PRODUCTS, EXACT TARGET AFFILIATE URLs, AND PRODUCT IMAGE URLs):\n" + "\n".join(prods_list) + "\n"

    prompt = f"""
You are an expert American pet technology reviewer and pet parent writing for PawGadgetHub.com.
Write an in-depth, authoritative, engaging, and SEO-optimized article targeting the primary keyword: "{keyword}".

{reviewed_products_text}
CRITICAL ACCURACY REQUIREMENT (REAL & LATEST DATA):
- Only review REAL, commercially available products on the US market (e.g., Petlibro, HoneyGuaridan, Whisker Litter-Robot, SureFlap, Catit, Tractive, Fi, Furbo, Petcube, PetSafe, WOPET).
- NEVER invent fictional product names or fake models.
- Provide accurate 2026 specifications: exact hopper capacity (Liters / Cups), power options (USB + battery backup type), Wi-Fi support (2.4GHz vs 5GHz), and honest build materials (BPA-free plastic vs SUS304 stainless steel).
- State any required monthly subscriptions transparently (e.g. cellular LTE fees for GPS collars or cloud video fees for pet cameras).
- STRICT PROHIBITION ON EM-DASHES AND EN-DASHES: NEVER output any em-dash (—) or en-dash (–) or double hyphens (--) in the title, headings, meta description, table, or body text. Instead, use colons, parentheses, commas, or clean periods.

INTERNAL & EXTERNAL LINKING STRATEGY:
1. Internal Links: You MUST integrate 2 to 4 natural, contextual internal links from this list using rich, descriptive anchor text (NEVER "click here"):
{internal_links_text}

2. External Authority Links: You MUST link to 2 to 3 authoritative, non-competing educational or veterinary resources to substantiate safety, nutrition, or tech claims:
- AAFCO (https://www.aafco.org) for nutrition/feeding standards
- ASPCA (https://www.aspca.org) or AVMA (https://www.avma.org) for pet safety and health guidance
- Cornell Feline Health Center (https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center) for cat behavior
Format external links with target="_blank" rel="noopener noreferrer".

Audience: US pet owners looking for real solutions, honest reviews, and reliable pet gadgets.
Tone: Friendly, authoritative, practical, and highly trustworthy (Brian Dean SEO + Google E-E-A-T guidelines).
Language: American English (use 'behavior', 'color', 'vet', 'leash', 'crate', 'ounces', 'lbs', '$').

Requirements:
1. Word count: STRICTLY 1,800 to 2,400 words. Write comprehensive, thorough paragraphs (at least 300-400 words per product review and 350+ words for the Buyer's Guide). Do NOT write thin summaries.
2. Structure & Visual Appearance (formatted in clean HTML using standard tags: <h2>, <h3>, <p>, <ul>, <li>, <table>, <strong>, <div>):
   - Engaging 150-200 word introduction addressing the real-world pet friction point and mentioning "{keyword}" naturally.
   - Styled "Quick Answer: Top Recommendations" Callout Box:
     <div style="background:#f8fafc;border-left:4px solid #f59e0b;padding:18px 22px;margin:24px 0;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.06);"><strong>Quick Answer: Top Recommendations</strong><p style="margin:8px 0 0;">...</p></div>
   - Styled Comparison Table (columns: Product, Best For, Rating, Approx. Price, Amazon Check) with <thead> and <tbody>. Put `<span class="pgh-badge pgh-badge--top">Top Pick</span>` on the #1 product, `<span class="pgh-badge pgh-badge--value">Best Value</span>` on the best budget pick, and a realistic star rating `<span class="pgh-rating">&#9733;&#9733;&#9733;&#9733;&#9733; 4.8/5</span>`. Link product names and "Check Price &rarr;" using each product's exact Target Affiliate URL.
   - Detailed Product Review Sections (one <h2> per product from the list above):
     * Immediately after the <h2>Product Name</h2>, include a rating line (`<p><span class="pgh-badge pgh-badge--top">Editor's Choice</span> <span class="pgh-rating">&#9733;&#9733;&#9733;&#9733;&#9733; 4.8/5</span></p>` for the #1 pick, or appropriate badge/rating) and if a Product Image URL is provided, embed:
       <div style="text-align:center;margin:20px 0 24px;padding:16px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;"><img src="PRODUCT_IMAGE_URL" alt="Product Name" style="max-height:280px;width:auto;max-width:100%;object-fit:contain;border-radius:8px;" /></div>
     * Key Specifications (<ul> bullets with concrete 2026 specs)
     * Why We Love It / Hands-On Impressions (detailed paragraph)
     * The Drawbacks / What Could Be Better (honest E-E-A-T critique)
     * Total Cost of Ownership (hardware price + any monthly subscription fees)
     * Who It's Best For
     * Clean direct button link: <p><a href="EXACT_TARGET_AFFILIATE_URL_FOR_THIS_PRODUCT" target="_blank" rel="nofollow noopener sponsored" style="display:inline-block;background:#f59e0b;color:#111827;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:bold;">Check Price on Amazon &rarr;</a></p>
       (Strictly use the exact Target Affiliate URL specified for that product above, e.g. SiteStripe shortlinks like https://link.amazon/...).
   - Comprehensive Buyer's Guide (<h2>Buyer's Guide: How to Choose...</h2> with 3-4 <h3> subheadings covering battery life, multi-pet households, cleaning ease, WiFi/LTE requirements).
   - <h2>Frequently Asked Questions</h2> with 4 real, practical <h3>Question?</h3> + <p>Direct Answer</p> pairs.
   - Styled Final Verdict box (<h2>Final Verdict</h2> + concise recommendation).

Return ONLY a valid JSON object matching this schema:
{{
  "title": "Compelling Click-Worthy SEO Title (Under 60 chars)",
  "slug": "url-friendly-slug-with-keyword",
  "meta_description": "Compelling meta description under 155 characters that includes the keyword and a call to action.",
  "image_search_query": "Single concise 3-word query for image search",
  "content_html": "<full HTML article content as specified above>",
  "tags": ["tag1", "tag2", "tag3"]
}}
"""
    return prompt

def generate_and_post_single(item_id=None, status="publish"):
    config = load_config()
    keywords = load_keywords()
    
    if not config.get("gemini_api_key"):
        print("[Error] Gemini API key is missing in config.json. Please provide your Gemini API key.")
        return False
        
    # Find item
    target_item = None
    if item_id is not None:
        for kw in keywords:
            if kw["id"] == item_id:
                target_item = kw
                break
    else:
        # Strictly prioritize pending posts where 100% of reviewed products have active shortlinks
        for kw in keywords:
            if kw.get("status") == "pending":
                prods = kw.get("reviewed_products", [])
                if prods and all("link.amazon" in p.get("affiliate_url", "") or "amzn.to" in p.get("affiliate_url", "") for p in prods):
                    target_item = kw
                    break
        # Fallback to next pending in queue
        if not target_item:
            for kw in keywords:
                if kw.get("status") == "pending":
                    target_item = kw
                    break
                
    if not target_item:
        print("[Notice] No pending keywords found in queue.")
        return False
        
    print(f"\n[1/6] Processing keyword #{target_item['id']}: \"{target_item['keyword']}\" (Category: {target_item['category']})")
    
    # Fetch live published posts for internal linking
    print("      Fetching existing live articles for internal linking silos...")
    live_posts = get_published_posts(config)
    print(f"      Found {len(live_posts)} live article(s) in database.")
    
    # 1. Gemini Content Generation with Real Data & Linking
    print("[2/6] Generating in-depth SEO content via Gemini API (Real specs + Link weaving)...")
    prompt = build_prompt_for_keyword(target_item, config.get("amazon_tag", "pawgadgethub-20"), live_posts, config)
    post_data = call_gemini(prompt, config["gemini_api_key"])
    
    # Programmatic sanitization to strictly enforce zero em-dashes and en-dashes
    def sanitize_text(text):
        if not isinstance(text, str):
            return text
        text = text.replace("—", ": ").replace("–", "-").replace("&mdash;", ": ").replace("&ndash;", "-")
        text = text.replace(" -- ", " : ").replace(" --", " -")
        return text

    post_data["title"] = sanitize_text(post_data.get("title", ""))
    post_data["meta_description"] = sanitize_text(post_data.get("meta_description", ""))
    post_data["content_html"] = sanitize_text(post_data.get("content_html", ""))

    # Programmatic enforcement: Guarantee user SiteStripe links, product images, and FAQ Schema
    if "reviewed_products" in target_item:
        import re
        for p in target_item["reviewed_products"]:
            asin = p.get("asin")
            aff_url = p.get("affiliate_url")
            img_url = p.get("image_url")
            prod_name = p.get("name", "")
            if asin and aff_url:
                pattern = r"https://(?:www\.)?amazon\.com/dp/" + re.escape(asin) + r'[^"\'<>\s]*'
                post_data["content_html"] = re.sub(pattern, aff_url, post_data["content_html"])
                post_data["content_html"] = post_data["content_html"].replace("EXACT_TARGET_AFFILIATE_URL_FOR_THIS_PRODUCT", aff_url)
            if img_url and img_url not in post_data["content_html"]:
                # Inject styled product image container right after the matching product H2
                img_block = f'\n<div style="text-align:center;margin:20px 0 24px;padding:16px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;"><img src="{img_url}" alt="{prod_name}" style="max-height:280px;width:auto;max-width:100%;object-fit:contain;border-radius:8px;" /></div>'
                first_word = prod_name.split()[0] if prod_name else ""
                if first_word:
                    h2_pattern = re.compile(r'(<h2[^>]*>[^<]*' + re.escape(first_word) + r'[^<]*</h2>)', re.IGNORECASE)
                    if h2_pattern.search(post_data["content_html"]):
                        post_data["content_html"] = h2_pattern.sub(r'\1' + img_block, post_data["content_html"], count=1)

    # Auto-generate FAQPage JSON-LD schema if FAQ Q&As exist
    import re
    faq_section = re.search(r'<h2[^>]*>Frequently Asked Questions.*?</h2>(.*?)(?:<h2|$)', post_data["content_html"], re.DOTALL | re.IGNORECASE)
    if faq_section:
        qa_pairs = re.findall(r'<h3[^>]*>(.*?)</h3>\s*<p[^>]*>(.*?)</p>', faq_section.group(1), re.DOTALL)
        if qa_pairs:
            faq_entities = []
            for q_html, a_html in qa_pairs:
                q_clean = re.sub(r'<[^>]+>', '', q_html).strip()
                a_clean = re.sub(r'<[^>]+>', '', a_html).strip()
                if q_clean and a_clean:
                    faq_entities.append({
                        "@type": "Question",
                        "name": q_clean,
                        "acceptedAnswer": {"@type": "Answer", "text": a_clean}
                    })
            if faq_entities:
                faq_schema = {"@context": "https://schema.org", "@type": "FAQPage", "mainEntity": faq_entities}
                post_data["content_html"] += f'\n<script type="application/ld+json">{json.dumps(faq_schema)}</script>'

    # Append sticky mobile bottom CTA bar for #1 Top Pick
    if target_item.get("reviewed_products"):
        top_prod = target_item["reviewed_products"][0]
        top_name = top_prod.get("name", "Top Pick")
        top_url = top_prod.get("affiliate_url", "")
        if top_url:
            sticky_cta = (
                f'\n<div class="pgh-sticky-cta">'
                f'<div class="pgh-sticky-cta__info"><span class="pgh-sticky-cta__label">&#9733; Top Pick</span>'
                f'<span class="pgh-sticky-cta__name">{top_name}</span></div>'
                f'<a class="pgh-sticky-cta__btn" href="{top_url}" target="_blank" rel="nofollow noopener sponsored">Check Price &rarr;</a>'
                f'</div>'
            )
            post_data["content_html"] += sticky_cta

    print(f"      Generated Title: {post_data['title']}")
    
    # 2. Image Selection & Fetching (Zero API keys needed)
    print(f"[3/6] Selecting royalty-free HD image for: \"{target_item['category']}\"...")
    image_info = fetch_royalty_free_image(target_item["category"], target_item["keyword"])
    
    # 3. WordPress Media Upload
    featured_media_id = None
    if image_info:
        print(f"      Downloading and uploading to WordPress Media Library...")
        featured_media_id = upload_image_to_wordpress(image_info, config)
        
    # 4. WordPress Post Creation
    print(f"[4/6] Publishing post to WordPress ({status})...")
    created_post = publish_to_wordpress(
        post_data=post_data,
        config=config,
        category_id=target_item["category_id"],
        featured_media_id=featured_media_id,
        status=status
    )
    post_url = created_post.get("link", f"{config['site_url']}/{post_data['slug']}")
    print(f"[Success] Post published live: {post_url} (ID: {created_post['id']})")
    
    # 4b. Set Rank Math SEO Focus Keyword, Title, and Description
    update_rank_math_meta(created_post["id"], target_item["keyword"], post_data["title"], post_data["meta_description"], config)
    
    # 5. Bidirectional Internal Linking (Update earlier posts to link to this new post)
    print(f"[5/6] Updating sibling articles with bidirectional backlinks...")
    inject_bidirectional_backlink(created_post["id"], post_data["title"], post_url, target_item["category_id"], config, live_posts)
    
    # 6. Instant Indexing Ping via Rank Math IndexNow
    print(f"[6/6] Notifying search engines via Rank Math Instant Indexing...")
    ping_indexnow(config["site_url"], post_url, config)
    
    # Update keyword status
    target_item["status"] = "published"
    target_item["post_id"] = created_post["id"]
    target_item["post_url"] = post_url
    target_item["published_at"] = datetime.now().isoformat()
    save_keywords(keywords)
    
    print(f"\n=== Done! Keyword #{target_item['id']} is live on {config['site_url']} ===\n")
    return True

def show_status():
    config = load_config()
    keywords = load_keywords()
    
    total = len(keywords)
    published = sum(1 for k in keywords if k.get("status") == "published")
    pending = sum(1 for k in keywords if k.get("status") == "pending")
    
    print("\n" + "="*50)
    print("🐾 PawGadgetHub.com Automation Pipeline Status")
    print("="*50)
    print(f"Site URL:          {config['site_url']}")
    print(f"WordPress User:    {config['wp_user']}")
    print(f"Gemini API Key:    {'[Configured]' if config.get('gemini_api_key') else '[Missing]'}")
    print(f"Pexels API Key:    {'[Configured]' if config.get('pexels_api_key') else '[Missing]'}")
    print(f"Amazon Tag:        {config.get('amazon_tag', 'None')}")
    print("-" * 50)
    print(f"Total Keywords:    {total}")
    print(f"Published Posts:   {published}")
    print(f"Pending Queue:     {pending}")
    print("="*50 + "\n")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="PawGadgetHub Automation Engine")
    parser.add_argument("--run-one", action="store_true", help="Generate and publish next pending post")
    parser.add_argument("--id", type=int, help="Run specific keyword ID")
    parser.add_argument("--status", action="store_true", help="Display current pipeline status")
    parser.add_argument("--draft", action="store_true", help="Save as draft instead of publishing")
    args = parser.parse_args()
    
    if args.status or len(sys.argv) == 1:
        show_status()
    elif args.run_one or args.id:
        status = "draft" if args.draft else "publish"
        generate_and_post_single(item_id=args.id, status=status)
