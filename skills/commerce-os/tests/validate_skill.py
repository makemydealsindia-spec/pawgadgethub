#!/usr/bin/env python3
"""Automated spec, link, and formatting validator for Commerce OS."""
import glob
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SKILL_MD = os.path.join(ROOT, "SKILL.md")
REF_DIR = os.path.join(ROOT, "references")

REQUIRED_LEADERS = [
    ("Alex Hormozi", "https://www.acquisition.com/training/offers4"),
    ("April Dunford", "https://www.aprildunford.com/post/a-quickstart-guide-to-positioning"),
    ("Joanna Wiebe", "https://copyhackers.com/conversion-copywriting-defined/"),
    ("Sabri Suby", "https://www.scoreapp.com/sabri-suby-leads-that-scale/"),
    ("Nik Sharma", "https://www.nik.co/topics/conversion"),
    ("Steve Schoger", "https://www.steveschoger.com/book/"),
    ("Chris Do", "https://thefutur.com/content/identity-design-branding"),
    ("Brian Dean", "https://backlinko.com/seo-tutorial"),
    ("Gary Vaynerchuk", "https://garyvaynerchuk.com/47-pages-that-will-change-your-entire-marketing-organization-approach/"),
    ("Ezra Firestone", "https://smartmarketer.com/smart-paid-traffic/"),
    ("Dara Denney", "https://motionapp.com/event/creative-strategy-workflow-with-dara-denney/"),
    ("Chase Dimond", "https://www.chasedimond.com/flows"),
    ("Peep Laja", "https://cxl.com/conversion-rate-optimization/how-to-create-a-cro-process-by-peep-laja/"),
]

def main():
    assert os.path.exists(SKILL_MD), "Missing SKILL.md"
    ref_files = sorted(glob.glob(os.path.join(REF_DIR, "*.md")))
    assert len(ref_files) == 8, f"Expected 8 reference files, found {len(ref_files)}"

    with open(SKILL_MD, "r", encoding="utf-8") as f:
        skill_text = f.read()

    assert skill_text.startswith("---\nname: commerce-os\n"), "Invalid YAML frontmatter name in SKILL.md"
    assert len(skill_text.splitlines()) < 500, "SKILL.md exceeds 500 lines"

    all_files = [SKILL_MD] + ref_files
    combined = ""
    for path in all_files:
        with open(path, "r", encoding="utf-8") as f:
            content = f.read()
        combined += "\n" + content
        assert "—" not in content and "–" not in content, f"Forbidden dash found in {path}"
        lines = content.splitlines()
        for i in range(1, len(lines)):
            curr = lines[i].lstrip()
            prev = lines[i - 1].strip()
            if curr.startswith("- ") or re.match(r"^1\.\s", curr):
                if prev != "" and not prev.startswith("-") and not re.match(r"^\d+\.\s", prev) and not prev.startswith("*"):
                    raise AssertionError(f"Missing blank line before list in {path}:{i+1}")

    for leader, url in REQUIRED_LEADERS:
        assert leader in combined, f"Missing leader: {leader}"
        assert url in combined, f"Missing canonical URL for {leader}: {url}"

    print("PASS: All 9 skill files, 13 operator playbooks, and formatting gates verified.")

if __name__ == "__main__":
    main()
