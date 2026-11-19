# 🐾 PawGadgetHub.com — Automated Affiliate Engine

An automated, SEO-optimized publishing pipeline for **PawGadgetHub.com** targeting high-intent US pet tech buyers.

---

## 📁 Project Structure

```
pawgadgethub/
├── config.json                 # WordPress credentials & API keys
├── keywords.json               # Validated keyword queue with KD & search volumes
├── automation_engine.py        # Core generator & auto-poster script
├── .github/workflows/
│   └── auto_publisher.yml     # Free scheduled publishing (Mon, Wed, Fri 9 AM EST)
└── README.md                   # This guide
```

---

## 🚀 How to Run Manually

### 1. Check Pipeline Status
```bash
python3 automation_engine.py --status
```

### 2. Generate and Publish the Next Pending Post Live
```bash
python3 automation_engine.py --run-one
```

### 3. Generate a Specific Keyword as a Draft
```bash
python3 automation_engine.py --id 1 --draft
```

---

## ⚡ IndexNow & Search Engine Ping
Every published post automatically notifies **Bing** and **Yandex** via the IndexNow protocol for instant indexing.
