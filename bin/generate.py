#!/usr/bin/env python3
"""
Generates every static HTML page for the Healthgists prototype from shared
header/nav/footer chrome + per-page content defined below.

Plain static HTML has no server-side include, so without this, the same
nav/footer markup would need hand-editing in ~10 files every time it
changes. Run this whenever chrome (nav/footer) or post/category data
changes:

    python3 bin/generate.py

Not shipped to the eventual WordPress theme — a build-time-only tool, same
role as phf-ogun's bin/*.php seed scripts.
"""
import os

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SITE_NAME = "Healthgists"
SITE_TAGLINE = "Health News, Tips & Insights"
BASE_URL = "https://healthgists.com"

CATEGORIES = [
    {"slug": "preventive-care", "name": "Preventive Care", "cover": "checkup-photo.jpg",
     "blurb": "Screenings, checkups, and the early-warning signs worth acting on."},
    {"slug": "genetics-family-health", "name": "Genetics & Family Health", "cover": "genotype-photo.jpg",
     "blurb": "Genotype, hereditary risk, and what to know before starting a family."},
    {"slug": "mental-health", "name": "Mental Health", "cover": "mentalhealth-photo.jpg",
     "blurb": "Mind and mood, treated with the same seriousness as the body."},
    {"slug": "nutrition-wellness", "name": "Nutrition & Wellness", "cover": "nutrition-photo.jpg",
     "blurb": "Food, habits, and everyday choices that move your numbers in the right direction."},
]
CAT_BY_SLUG = {c["slug"]: c for c in CATEGORIES}

POSTS = [
    {
        "slug": "5-early-warning-signs-you-shouldnt-ignore",
        "title": "5 Early Warning Signs You Shouldn’t Ignore",
        "excerpt": "Fatigue, thirst, and a few other “minor” symptoms are often the body’s earliest way of flagging something worth a proper test.",
        "category": "preventive-care",
        "cover": "checkup-photo.jpg",
        "author": "Healthgists Medical Desk",
        "date": "2026-08-14",
        "read_time": "5 min read",
        "body": """
<p>Most serious health conditions don’t announce themselves loudly. They start as symptoms easy to explain away — tiredness blamed on a busy week, thirst blamed on the heat, a headache blamed on screen time. The problem is that some of the most common chronic conditions in Nigeria today, from diabetes to hypertension to kidney disease, tend to be caught late precisely because their early signs look so ordinary.</p>

<p>Here are five symptoms worth taking seriously enough to get checked, not diagnosed by guesswork.</p>

<h2>1. Persistent fatigue that doesn’t improve with rest</h2>
<p>Everyone gets tired. But fatigue that lingers for weeks, doesn’t improve after a good night’s sleep, or shows up even on light days can point to anaemia, thyroid issues, or early-stage diabetes. A Full Blood Count and fasting blood sugar test are usually the first, most affordable places to start.</p>

<h2>2. Unusual thirst and frequent urination</h2>
<p>Drinking more water than usual and needing the bathroom more often — especially at night — is one of the earliest and most reliable signs of elevated blood sugar. This is often dismissed until symptoms become severe, by which point management is harder.</p>

<h2>3. Unexplained weight change</h2>
<p>Losing or gaining weight without a change in diet or activity is the body signalling that something metabolic or hormonal is off. Thyroid function tests and a basic hormonal panel can usually narrow down the cause quickly.</p>

<h2>4. Persistent headaches or dizziness</h2>
<p>Occasional headaches are normal. Headaches that are frequent, worse in the morning, or paired with dizziness can be an early sign of high blood pressure — a condition that, left unchecked, is one of the leading causes of stroke and kidney damage in Nigeria.</p>

<h2>5. Slow-healing cuts or frequent infections</h2>
<p>If small wounds are taking noticeably longer to heal, or you’re catching infections more often than usual, it’s worth having your blood sugar and immune markers checked. This is often one of the first visible signs of undiagnosed diabetes.</p>

<h2>The bottom line</h2>
<p>None of these symptoms are a diagnosis on their own — and that’s exactly the point. A simple panel of tests (often under ₦10,000) can turn a vague symptom into a clear answer, months or years before it becomes a harder problem to solve. Early detection remains the single most effective tool in preventive healthcare.</p>

<blockquote>If something in your body has felt “off” for more than two weeks, that’s reason enough to get it looked at — not a reason to wait until it gets worse.</blockquote>
""",
    },
    {
        "slug": "genotype-and-marriage-what-nigerian-couples-should-know",
        "title": "Genotype and Marriage: What Every Nigerian Couple Should Know Before Saying “I Do”",
        "excerpt": "A genotype test costs a fraction of what a wedding does — and it’s one of the few premarital checks that can change the course of a family’s health for generations.",
        "category": "genetics-family-health",
        "cover": "genotype-photo.jpg",
        "author": "Healthgists Medical Desk",
        "date": "2026-07-02",
        "read_time": "6 min read",
        "body": """
<p>In much of Nigeria, genotype compatibility is a familiar phrase — mentioned at introductions, half-joked about, occasionally taken as seriously as it should be. But for a country with one of the highest burdens of sickle cell disease in the world, it deserves more than a passing mention.</p>

<h2>What genotype actually measures</h2>
<p>Your genotype describes the type of haemoglobin genes you carry — commonly AA, AS, AC, or SS. AA is considered the “normal” genotype with no sickle cell trait. AS and AC are carriers — generally healthy themselves, but able to pass the trait to children. SS is sickle cell disease itself, a lifelong condition involving pain crises, organ strain, and reduced life expectancy without proper management.</p>

<h2>Why it matters most as a couple</h2>
<p>Genotype only becomes a serious risk in combination. Two AS carriers — each individually healthy — have a 25% chance, with every pregnancy, of having a child with SS (sickle cell disease). Neither partner may show any symptoms themselves, which is exactly why so many couples only discover compatibility risk after a child is already affected.</p>

<table>
<tr><th>Combination</th><th>Risk to children</th></tr>
<tr><td>AA + AA</td><td>No risk of sickle cell disease</td></tr>
<tr><td>AA + AS</td><td>No SS risk, but 50% chance of carrying the trait</td></tr>
<tr><td>AS + AS</td><td>25% chance of SS per pregnancy</td></tr>
<tr><td>AS + SS</td><td>50% chance of SS per pregnancy</td></tr>
<tr><td>SS + SS</td><td>All children will have SS</td></tr>
</table>

<h2>It’s not about stopping a marriage</h2>
<p>Genotype testing isn’t about telling couples who they can or can’t marry — that’s a personal and often deeply emotional decision. It’s about removing guesswork from it. Couples who know their combined risk ahead of time can make informed choices: family planning options, early prenatal screening, or simply going in with full information instead of finding out after the fact.</p>

<h2>The test itself is simple</h2>
<p>A genotype test requires a small blood sample and typically returns results same-day. It’s one of the most affordable, highest-impact tests available — a single visit that can shape the health of an entire family line.</p>

<blockquote>Knowing your genotype doesn’t decide your relationship for you. It just makes sure the decision is an informed one.</blockquote>

<p>If you’re planning a wedding, or already married and haven’t had this conversation, there’s no better time than now — for you, and for whoever comes after you.</p>
""",
    },
    {
        "slug": "why-mental-health-checkins-matter",
        "title": "Why Regular Mental Health Check-Ins Matter as Much as Physical Ones",
        "excerpt": "We routinely check blood pressure and blood sugar. Mental health deserves the same rhythm of attention — not just a reaction to crisis.",
        "category": "mental-health",
        "cover": "mentalhealth-photo.jpg",
        "author": "Healthgists Medical Desk",
        "date": "2026-06-18",
        "read_time": "4 min read",
        "body": """
<p>It’s common to schedule a physical checkup once a year — blood pressure, blood sugar, a general once-over. It’s far less common to give mental health the same routine attention, even though the evidence linking the two is strong: chronic stress raises blood pressure, poor sleep disrupts metabolism, and untreated anxiety or depression measurably worsens physical recovery from illness.</p>

<h2>Mental health isn’t just the absence of crisis</h2>
<p>A lot of us only think about mental health when something goes visibly wrong — a breakdown, a diagnosis, a crisis point. But mental health, like physical health, exists on a spectrum, and it responds well to the same principle: catching small shifts early is easier than managing a full-blown episode later.</p>

<h2>What a check-in can look like</h2>
<ul>
<li><strong>A conversation with a professional</strong> — even one session with a counsellor or therapist, unconnected to any specific crisis, can surface patterns worth addressing.</li>
<li><strong>Honest self-tracking</strong> — noticing patterns in sleep, appetite, irritability, or motivation over weeks, not just days.</li>
<li><strong>Physical markers</strong> — persistent fatigue, appetite changes, and sleep disruption are often physical symptoms with a mental health root.</li>
</ul>

<h2>The stigma is loosening, slowly</h2>
<p>Mental healthcare in Nigeria still carries stigma that physical healthcare mostly doesn’t. But that’s changing, and normalising a regular check-in — the same way you’d normalise an annual blood test — is part of how it changes faster.</p>

<blockquote>You don’t need to be in crisis to justify checking in on your mental health. That’s exactly the point of a check-in.</blockquote>

<p>If it’s been a while since you’ve genuinely asked yourself how you’re doing — not the reflexive “fine,” but a real answer — that’s worth a conversation with someone qualified to help you unpack it.</p>
""",
    },
    {
        "slug": "nigerian-foods-for-healthy-blood-pressure",
        "title": "5 Nigerian Foods That Naturally Support Healthy Blood Pressure",
        "excerpt": "Managing blood pressure doesn’t require giving up local food — it requires knowing which everyday ingredients are already working in your favour.",
        "category": "nutrition-wellness",
        "cover": "nutrition-photo.jpg",
        "author": "Healthgists Medical Desk",
        "date": "2026-05-27",
        "read_time": "5 min read",
        "body": """
<p>Hypertension is one of the most common — and most under-diagnosed — conditions in Nigeria. The good news is that diet is one of the most controllable levers available, and several ingredients already common in Nigerian kitchens are genuinely useful for managing blood pressure, not just folklore.</p>

<h2>1. Ugu (fluted pumpkin leaf)</h2>
<p>Rich in potassium and magnesium, both of which help the body regulate sodium balance and relax blood vessel walls. A regular pot of ugu soup, prepared with modest salt, is a genuinely solid dietary habit.</p>

<h2>2. Garden egg</h2>
<p>Garden egg is high in fibre and contains compounds that support healthy cholesterol levels, which works alongside blood pressure management rather than against it.</p>

<h2>3. Unripe plantain</h2>
<p>Lower on the glycaemic index than ripe plantain, and a good source of potassium. Boiled or roasted (not deep-fried) is the better preparation for blood pressure specifically.</p>

<h2>4. Tiger nuts (aya)</h2>
<p>A good source of potassium and healthy fats, tiger nuts (often blended into a milk-like drink) are a useful snack alternative to processed, salt-heavy options.</p>

<h2>5. Hibiscus (zobo)</h2>
<p>Multiple studies have linked hibiscus tea to modest reductions in blood pressure. The catch: many commercial zobo preparations are loaded with sugar, which undermines the benefit. Homemade, lightly sweetened zobo is the better version.</p>

<h2>What to watch, alongside what to add</h2>
<p>Adding these foods matters less if sodium intake from bouillon cubes, processed seasoning, and salt-cured proteins stays high. Blood pressure management works best as a combination: more potassium-rich whole foods, less processed sodium, and a blood pressure check often enough to know whether it’s actually working.</p>

<blockquote>Diet changes are most powerful when paired with a number to track against — get your blood pressure checked, adjust, and check again.</blockquote>
""",
    },
]

NAV_LINKS = [
    ("Home", "/index.html"),
    ("Blog", "/blog.html"),
    ("About", "/about.html"),
    ("Contact", "/contact.html"),
]


def rel(path_from_root, current_dir):
    """Relative path from a page in current_dir (relative to ROOT) to path_from_root."""
    if current_dir in ("", "."):
        return path_from_root.lstrip("/")
    depth = current_dir.count("/") + 1
    return ("../" * depth) + path_from_root.lstrip("/")


def head(title, description, current_dir, canonical_path, og_image="assets/images/brand/og-image.png"):
    return f"""<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{title}</title>
<meta name="description" content="{description}" />
<link rel="canonical" href="{BASE_URL}{canonical_path}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{SITE_NAME}" />
<meta property="og:title" content="{title}" />
<meta property="og:description" content="{description}" />
<meta property="og:url" content="{BASE_URL}{canonical_path}" />
<meta property="og:image" content="{BASE_URL}/{og_image}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{title}" />
<meta name="twitter:description" content="{description}" />
<link rel="icon" type="image/svg+xml" href="{rel('assets/images/brand/icon-blue.svg', current_dir)}" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Commissioner:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{rel('assets/css/style.build.css', current_dir)}" />
</head>
"""


def breadcrumbs(trail, current_dir):
    """trail: list of (label, path_from_root_or_None). None path = current page, not linked."""
    crumb_items = []
    ld_items = []
    for i, (label, path) in enumerate(trail):
        ld_items.append(
            f'{{"@type":"ListItem","position":{i + 1},"name":"{label}"'
            + (f',"item":"{BASE_URL}/{path}"' if path else "")
            + "}"
        )
        if path:
            crumb_items.append(
                f'<a href="{rel(path, current_dir)}" class="text-ink/50 hover:text-green">{label}</a>'
            )
        else:
            crumb_items.append(f'<span class="font-medium text-ink/80">{label}</span>')
    nav = f"""<nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs">
      {'<span class="text-ink/30">/</span>'.join(crumb_items)}
    </nav>"""
    ld = f"""<script type="application/ld+json">
{{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{",".join(ld_items)}]}}
</script>"""
    return nav, ld


def header_nav(current_dir, active=""):
    logo = rel("assets/images/brand/logo-green.svg", current_dir)
    home = rel("index.html", current_dir)
    links = ""
    big_links = ""
    for label, href in NAV_LINKS:
        target = rel(href.lstrip("/"), current_dir)
        is_active = "text-green" if label.lower() == active.lower() else "text-ink/70 hover:text-ink"
        links += f'<a href="{target}" class="text-sm font-semibold {is_active} transition-colors">{label}</a>\n'
        big_links += f'<a href="{target}" data-nav-link class="border-b border-ink/10 py-4 text-2xl font-semibold {is_active}">{label}</a>\n'
    return f"""<header class="sticky top-0 z-40 border-b border-ink/10 bg-paper/90 backdrop-blur">
  <div class="container-xw flex h-20 items-center justify-between">
    <a href="{home}" class="flex items-center gap-2">
      <img src="{logo}" alt="{SITE_NAME}" class="h-8 w-auto" />
    </a>
    <nav class="hidden items-center gap-8 md:flex">
      {links}
    </nav>
    <div class="hidden md:block">
      <a href="{rel('blog.html', current_dir)}" class="inline-flex items-center justify-center rounded-full bg-green px-5 py-2.5 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5">Read the Blog</a>
    </div>
    <button data-nav-toggle aria-expanded="false" aria-label="Open menu" class="flex h-10 w-10 items-center justify-center rounded-full border border-ink/15 md:hidden">
      <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
  </div>

  <!-- Mobile nav: full-screen popover, not an inline dropdown. -->
  <div data-nav-menu class="fixed inset-0 z-50 hidden flex-col bg-paper md:hidden">
    <div class="container-xw flex h-20 items-center justify-between border-b border-ink/10">
      <a href="{home}" class="flex items-center gap-2">
        <img src="{logo}" alt="{SITE_NAME}" class="h-8 w-auto" />
      </a>
      <button data-nav-close aria-label="Close menu" class="flex h-10 w-10 items-center justify-center rounded-full border border-ink/15">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
      </button>
    </div>
    <nav class="container-xw flex flex-1 flex-col justify-center gap-1">
      {big_links}
    </nav>
    <div class="container-xw pb-10">
      <a href="{rel('blog.html', current_dir)}" class="flex items-center justify-center rounded-full bg-green px-5 py-3.5 text-sm font-semibold text-white">Read the Blog</a>
    </div>
  </div>
</header>
"""


def footer(current_dir):
    logo = rel("assets/images/brand/logo-white.svg", current_dir)
    cat_links = ""
    for c in CATEGORIES:
        cat_links += f'<li><a href="{rel(f"category/{c["slug"]}.html", current_dir)}" class="hover:text-white">{c["name"]}</a></li>\n'
    return f"""<footer class="bg-ink text-white/70">
  <div class="container-xw grid grid-cols-1 gap-10 py-16 md:grid-cols-4">
    <div class="md:col-span-2">
      <img src="{logo}" alt="{SITE_NAME}" class="h-8 w-auto" />
      <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/55">{SITE_TAGLINE} — evidence-based health articles, wellness tips, and medical news, curated by professionals.</p>
      <div class="mt-6 flex gap-3">
        <a href="https://web.facebook.com/profile.php?id=61589312589012" target="_blank" rel="noreferrer" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 transition-colors hover:border-white/40 hover:text-white">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="https://www.instagram.com/healthgistsnigeria/" target="_blank" rel="noreferrer" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 transition-colors hover:border-white/40 hover:text-white">
          <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
        </a>
      </div>
    </div>
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Categories</p>
      <ul class="mt-4 space-y-2 text-sm">
        {cat_links}
      </ul>
    </div>
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Site</p>
      <ul class="mt-4 space-y-2 text-sm">
        <li><a href="{rel('about.html', current_dir)}" class="hover:text-white">About</a></li>
        <li><a href="{rel('contact.html', current_dir)}" class="hover:text-white">Contact</a></li>
        <li><a href="{rel('blog.html', current_dir)}" class="hover:text-white">Blog</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-white/10">
    <div class="container-xw flex flex-col gap-2 py-6 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between">
      <span>&copy; {SITE_NAME} — all rights reserved.</span>
      <span>Built by <a href="https://husnic.com" target="_blank" rel="noreferrer" class="text-white/60 hover:text-white">Husnic Consulting</a></span>
    </div>
  </div>
</footer>
"""


def scripts(current_dir):
    return f'<script src="{rel("assets/js/main.js", current_dir)}"></script>\n'


def newsletter_block(variant="light"):
    bg = "bg-ink text-white" if variant == "dark" else "bg-lavender"
    return f"""<section class="bleed {bg}">
  <div class="container-xw py-16 md:py-20">
    <div class="mx-auto max-w-xl text-center">
      <h2 class="text-2xl font-bold md:text-3xl {'text-white' if variant=='dark' else 'text-ink'}">Never miss a health update</h2>
      <p class="mt-3 text-sm {'text-white/60' if variant=='dark' else 'text-ink/60'}">Evidence-based articles, straight to your inbox. No spam, unsubscribe anytime.</p>
      <form data-newsletter-form class="mt-6 flex flex-col gap-3 sm:flex-row">
        <input type="email" required placeholder="Enter your email address" class="w-full rounded-full border border-ink/15 bg-white px-5 py-3 text-sm text-ink outline-none focus:border-blue" />
        <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5">Subscribe</button>
      </form>
      <p data-newsletter-status class="mt-3 hidden text-sm"></p>
    </div>
  </div>
</section>
"""


def post_card(post, current_dir, featured=False):
    # A card is clickable everywhere, but the category chip needs its own,
    # separate link — nesting <a> inside <a> is invalid HTML and makes
    # browsers silently close the outer link early, which visually shatters
    # the card. Instead: a plain <div>, one real link on the title using a
    # ::after "stretched link" to cover the whole card, and the category
    # chip as an independent link raised above it with z-10.
    cat = CAT_BY_SLUG[post["category"]]
    cover = rel(f"assets/images/covers/{post['cover']}", current_dir)
    href = rel(f"posts/{post['slug']}.html", current_dir)
    cat_href = rel(f"category/{cat['slug']}.html", current_dir)
    if featured:
        return f"""<div class="group relative grid grid-cols-1 overflow-hidden rounded-3xl border border-ink/10 bg-white transition-shadow hover:shadow-xl hover:shadow-ink/5 md:grid-cols-2">
  <div class="aspect-[4/3] overflow-hidden">
    <img src="{cover}" alt="" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
  </div>
  <div class="flex flex-col justify-center p-8 md:p-10">
    <a href="{cat_href}" class="font-sans relative z-10 inline-block w-fit rounded-full bg-green/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green hover:bg-green/20">{cat['name']}</a>
    <h2 class="mt-4 text-2xl font-bold leading-snug text-ink md:text-3xl group-hover:text-green">
      <a href="{href}" class="after:absolute after:inset-0">{post['title']}</a>
    </h2>
    <p class="mt-3 text-sm leading-relaxed text-ink/60">{post['excerpt']}</p>
    <div class="font-sans mt-5 flex items-center gap-3 text-xs font-medium text-ink/45">
      <span>{post['author']}</span><span>&middot;</span><span>{post['read_time']}</span>
    </div>
  </div>
</div>
"""
    return f"""<div class="group relative flex flex-col overflow-hidden rounded-2xl border border-ink/10 bg-white transition-shadow hover:shadow-xl hover:shadow-ink/5">
  <div class="aspect-[16/10] overflow-hidden">
    <img src="{cover}" alt="" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
  </div>
  <div class="flex flex-1 flex-col p-6">
    <a href="{cat_href}" class="font-sans relative z-10 inline-block w-fit rounded-full bg-green/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green hover:bg-green/20">{cat['name']}</a>
    <h3 class="mt-3 text-lg font-bold leading-snug text-ink group-hover:text-green">
      <a href="{href}" class="after:absolute after:inset-0">{post['title']}</a>
    </h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed text-ink/60">{post['excerpt']}</p>
    <div class="font-sans mt-4 flex items-center gap-3 text-xs font-medium text-ink/45">
      <span>{post['author']}</span><span>&middot;</span><span>{post['read_time']}</span>
    </div>
  </div>
</div>
"""


def write(path, content):
    full = os.path.join(ROOT, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, "w") as f:
        f.write(content)
    print(f"wrote {path}")


def category_card(cat, current_dir):
    cover = rel(f"assets/images/covers/{cat['cover']}", current_dir)
    href = rel(f"category/{cat['slug']}.html", current_dir)
    return f"""<a href="{href}" class="group relative flex h-56 flex-col justify-end overflow-hidden rounded-3xl p-6 shadow-lg shadow-ink/5">
  <img src="{cover}" alt="" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
  <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/10 to-transparent"></div>
  <div class="relative">
    <h3 class="text-lg font-bold text-white">{cat['name']}</h3>
    <p class="mt-1 text-xs leading-relaxed text-white/70">{cat['blurb']}</p>
  </div>
</a>
"""


def value_prop(icon_svg, title, body, tint):
    return f"""<div>
  <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-{tint}/15 text-{tint}">
    {icon_svg}
  </div>
  <h3 class="mt-4 font-bold text-white">{title}</h3>
  <p class="mt-2 text-sm leading-relaxed text-white/55">{body}</p>
</div>
"""


ICON_CHECK = '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 12.5 9.5 17 19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>'
ICON_GLOBE = '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="8"/><path d="M4 12h16M12 4c2.2 2.2 3.4 5 3.4 8s-1.2 5.8-3.4 8c-2.2-2.2-3.4-5-3.4-8s1.2-5.8 3.4-8Z"/></svg>'
ICON_SPARK = '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18" stroke-linecap="round"/></svg>'


def hero_brand_slide(cur):
    return f"""<div data-hero-slide class="absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-1000 ease-in-out">
  <img src="{rel('assets/images/covers/checkup-photo.jpg', cur)}" alt="" class="h-full w-full object-cover" />
  <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/55 to-ink/20"></div>
  <div class="container-xw absolute inset-0 flex flex-col justify-end pb-20 md:pb-28">
    <div class="max-w-xl">
      <span class="font-sans inline-flex w-fit items-center gap-2 rounded-full bg-gold/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gold">Trusted health journalism</span>
      <h1 class="mt-5 text-4xl font-extrabold leading-tight text-white md:text-5xl">Health news and insights you can actually act on.</h1>
      <p class="mt-5 max-w-lg text-base leading-relaxed text-white/75">Evidence-based articles, wellness tips, and medical news — curated by professionals, written for real life.</p>
      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{rel('blog.html', cur)}" class="font-sans inline-flex items-center justify-center rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5">Start Reading</a>
        <a href="{rel('about.html', cur)}" class="font-sans inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white transition-all hover:border-white/70">About Healthgists</a>
      </div>
    </div>
  </div>
</div>
"""


def hero_post_slide(post, cur):
    cat = CAT_BY_SLUG[post["category"]]
    return f"""<div data-hero-slide class="absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-1000 ease-in-out">
  <img src="{rel(f"assets/images/covers/{post['cover']}", cur)}" alt="" class="h-full w-full object-cover" />
  <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/55 to-ink/20"></div>
  <div class="container-xw absolute inset-0 flex flex-col justify-end pb-20 md:pb-28">
    <div class="max-w-xl">
      <span class="font-sans inline-flex w-fit items-center gap-2 rounded-full bg-green/25 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">{cat['name']}</span>
      <h2 class="mt-5 text-3xl font-extrabold leading-tight text-white md:text-5xl">{post['title']}</h2>
      <p class="mt-5 max-w-lg text-base leading-relaxed text-white/75">{post['excerpt']}</p>
      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{rel(f"posts/{post['slug']}.html", cur)}" class="font-sans inline-flex items-center justify-center rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5">Read Article</a>
      </div>
    </div>
  </div>
</div>
"""


def build_index():
    cur = ""
    hero_posts = [POSTS[2], POSTS[1]]  # mental health, genotype — the other two hero slides
    posts_grid = "\n".join(post_card(p, cur) for p in POSTS)
    cat_cards = "\n".join(category_card(c, cur) for c in CATEGORIES)
    hero_slides = hero_brand_slide(cur) + "".join(hero_post_slide(p, cur) for p in hero_posts)
    slide_count = 1 + len(hero_posts)
    dots = "\n".join(
        f'<button type="button" data-hero-dot aria-label="Show slide {i + 1}" class="h-1.5 rounded-full transition-all duration-300 {"w-6 bg-gold" if i == 0 else "w-1.5 bg-white/40"}"></button>'
        for i in range(slide_count)
    )
    html = head(
        f"{SITE_NAME} — {SITE_TAGLINE}",
        "Your trusted destination for health news, medical insights, wellness tips, and expert health publications.",
        cur, "/",
    ) + f"""<body>
{header_nav(cur, "Home")}

<section class="bleed relative min-h-[85vh] overflow-hidden bg-ink md:min-h-[90vh]">
  <div data-hero-slides class="absolute inset-0">
    {hero_slides}
  </div>
  <div class="absolute bottom-6 left-1/2 z-10 flex -translate-x-1/2 gap-2 md:bottom-8">
    {dots}
  </div>
</section>

<section class="bleed bg-paper-warm py-16 md:py-20">
  <div class="container-xw">
    <div class="flex flex-wrap items-end justify-between gap-4">
      <div>
        <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-green">Explore</p>
        <h2 class="mt-2 text-2xl font-bold text-ink md:text-3xl">Browse by Topic</h2>
      </div>
    </div>
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      {cat_cards}
    </div>
  </div>
</section>

<section class="bleed bg-ink py-16 md:py-20">
  <div class="container-xw">
    <div class="max-w-lg">
      <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-white/50">Editorial standards</p>
      <h2 class="mt-2 text-2xl font-bold text-white md:text-3xl">Why readers trust Healthgists</h2>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-10 sm:grid-cols-3">
      {value_prop(ICON_CHECK, "Evidence-based", "Every article is grounded in established medical understanding, not speculation or trends.", "green")}
      {value_prop(ICON_GLOBE, "Locally relevant", "Written with the Nigerian context in mind — our food, our healthcare access, our realities.", "blue")}
      {value_prop(ICON_SPARK, "Actionable", "We favour practical takeaways over alarm — what to actually do with the information.", "gold")}
    </div>
  </div>
</section>

<section class="py-16 md:py-24">
  <div class="container-xw">
    <div class="flex flex-wrap items-end justify-between gap-4">
      <h2 class="text-2xl font-bold text-ink md:text-3xl">Latest Articles</h2>
      <a href="{rel('blog.html', cur)}" class="font-sans text-sm font-semibold text-green hover:underline">View all articles &rarr;</a>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {posts_grid}
    </div>
  </div>
</section>

{newsletter_block()}
{footer(cur)}
{scripts(cur)}
</body>
</html>
"""
    write("index.html", html)


def build_blog():
    cur = ""
    cards = "\n".join(post_card(p, cur) for p in POSTS)
    cat_chips = "\n".join(
        f'<a href="{rel(f"category/{c["slug"]}.html", cur)}" class="rounded-full border border-ink/12 bg-white px-4 py-2 text-sm font-medium text-ink/70 transition-colors hover:border-green hover:text-green">{c["name"]}</a>'
        for c in CATEGORIES
    )
    html = head(
        f"Blog — {SITE_NAME}",
        "All articles from Healthgists: health news, wellness tips, and medical insights.",
        cur, "/blog.html",
    ) + f"""<body>
{header_nav(cur, "Blog")}

<section class="border-b border-ink/10 bg-white py-14 md:py-16">
  <div class="container-xw">
    <h1 class="text-3xl font-extrabold text-ink md:text-4xl">The Blog</h1>
    <p class="mt-3 max-w-xl text-sm text-ink/60">Every article, in one place — filter by topic or just start scrolling.</p>
    <nav aria-label="Filter by category" class="mt-7 flex flex-wrap gap-3">
      <a href="{rel('blog.html', cur)}" class="rounded-full bg-green px-4 py-2 text-sm font-semibold text-white">All</a>
      {cat_chips}
    </nav>
  </div>
</section>

<section class="py-16 md:py-20">
  <div class="container-xw">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {cards}
    </div>
  </div>
</section>

{newsletter_block()}
{footer(cur)}
{scripts(cur)}
</body>
</html>
"""
    write("blog.html", html)


def build_post(post):
    cur = "posts"
    cat = CAT_BY_SLUG[post["category"]]
    others = [p for p in POSTS if p is not post][:3]
    related = "\n".join(post_card(p, cur) for p in others)
    crumb_nav, crumb_ld = breadcrumbs(
        [
            ("Home", "index.html"),
            ("Blog", "blog.html"),
            (cat["name"], f"category/{cat['slug']}.html"),
        ],
        cur,
    )
    html = head(
        f"{post['title']} — {SITE_NAME}",
        post["excerpt"],
        cur, f"/posts/{post['slug']}.html",
        og_image=f"assets/images/covers/{post['cover']}",
    ) + f"""<body>
{crumb_ld}
{header_nav(cur, "Blog")}

<article>
  <section class="border-b border-ink/10 bg-white py-12 md:py-16">
    <div class="container-xw mx-auto max-w-3xl">
      {crumb_nav}
      <a href="{rel(f'category/{cat["slug"]}.html', cur)}" class="font-sans mt-4 inline-block rounded-full bg-green/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green">{cat['name']}</a>
      <h1 class="mt-5 text-3xl font-extrabold leading-tight text-ink md:text-4xl">{post['title']}</h1>
      <div class="font-sans mt-5 flex items-center gap-3 text-sm text-ink/50">
        <span class="font-semibold text-ink/70">{post['author']}</span><span>&middot;</span><span>{post['date']}</span><span>&middot;</span><span>{post['read_time']}</span>
      </div>
    </div>
  </section>

  <div class="container-xw mx-auto -mt-6 max-w-4xl md:-mt-10">
    <div class="overflow-hidden rounded-3xl shadow-xl shadow-ink/10">
      <img src="{rel(f"assets/images/covers/{post['cover']}", cur)}" alt="" class="aspect-[16/8] w-full object-cover" />
    </div>
  </div>

  <section class="py-14 md:py-16">
    <div class="container-xw mx-auto max-w-2xl">
      <div class="article-body">
        {post['body']}
      </div>

      <div class="font-sans mt-12 flex items-center gap-4 rounded-2xl border border-ink/10 bg-paper-warm p-6">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green text-sm font-bold text-white">HG</div>
        <div>
          <p class="text-sm font-semibold text-ink">{post['author']}</p>
          <p class="text-xs text-ink/50">Reviewed for accuracy by the Healthgists editorial team.</p>
        </div>
      </div>
    </div>
  </section>
</article>

<section class="border-t border-ink/10 py-16 md:py-20">
  <div class="container-xw">
    <h2 class="text-2xl font-bold text-ink">More from Healthgists</h2>
    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {related}
    </div>
  </div>
</section>

{newsletter_block()}
{footer(cur)}
{scripts(cur)}
</body>
</html>
"""
    write(f"posts/{post['slug']}.html", html)


def build_category(cat):
    cur = "category"
    posts = [p for p in POSTS if p["category"] == cat["slug"]]
    cards = "\n".join(post_card(p, cur) for p in posts) if posts else '<p class="text-sm text-ink/50">No articles in this category yet — check back soon.</p>'
    html = head(
        f"{cat['name']} — {SITE_NAME}",
        cat["blurb"],
        cur, f"/category/{cat['slug']}.html",
        og_image=f"assets/images/covers/{cat['cover']}",
    ) + f"""<body>
{header_nav(cur, "Blog")}

<section class="bleed" style="background-image:url('{rel(f"assets/images/covers/{cat['cover']}", cur)}');background-size:cover;background-position:center;">
  <div class="bg-ink/55 py-20 md:py-24">
    <div class="container-xw">
      <p class="font-sans text-xs font-semibold uppercase tracking-[0.25em] text-white/70">Category</p>
      <h1 class="mt-3 text-3xl font-extrabold text-white md:text-4xl">{cat['name']}</h1>
      <p class="mt-3 max-w-xl text-sm text-white/75">{cat['blurb']}</p>
    </div>
  </div>
</section>

<section class="py-16 md:py-20">
  <div class="container-xw">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {cards}
    </div>
  </div>
</section>

{newsletter_block()}
{footer(cur)}
{scripts(cur)}
</body>
</html>
"""
    write(f"category/{cat['slug']}.html", html)


def build_about():
    cur = ""
    html = head(
        f"About — {SITE_NAME}",
        "Healthgists exists to make evidence-based health information accessible, accurate, and easy to act on.",
        cur, "/about.html",
    ) + f"""<body>
{header_nav(cur, "About")}

<section class="border-b border-ink/10 bg-white py-16 md:py-24">
  <div class="container-xw mx-auto max-w-3xl text-center">
    <span class="font-sans inline-flex items-center gap-2 rounded-full bg-green/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green">About Healthgists</span>
    <h1 class="mt-5 text-3xl font-extrabold text-ink md:text-5xl">Health information you can trust, in language you actually understand.</h1>
    <p class="mt-6 text-base leading-relaxed text-ink/60">Healthgists is a health publication built on one belief: that good health decisions start with good information — clear, evidence-based, and free of unnecessary jargon or fear-mongering.</p>
  </div>
  <div class="container-xw mx-auto mt-12 max-w-3xl">
    <div class="overflow-hidden rounded-3xl shadow-xl shadow-ink/10">
      <img src="{rel('assets/images/covers/pills-photo.jpg', cur)}" alt="" class="aspect-[16/7] w-full object-cover" />
    </div>
  </div>
</section>

<section class="py-16 md:py-20">
  <div class="container-xw mx-auto grid max-w-4xl grid-cols-1 gap-10 md:grid-cols-3">
    <div>
      <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green/10 text-green">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 12.5 9.5 17 19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </div>
      <h3 class="mt-4 font-bold text-ink">Evidence-based</h3>
      <p class="mt-2 text-sm leading-relaxed text-ink/60">Every article is grounded in established medical understanding, not speculation or trends.</p>
    </div>
    <div>
      <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue/10 text-blue">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="8"/><path d="M4 12h16M12 4c2.2 2.2 3.4 5 3.4 8s-1.2 5.8-3.4 8c-2.2-2.2-3.4-5-3.4-8s1.2-5.8 3.4-8Z"/></svg>
      </div>
      <h3 class="mt-4 font-bold text-ink">Locally relevant</h3>
      <p class="mt-2 text-sm leading-relaxed text-ink/60">Written with the Nigerian context in mind — our food, our healthcare access, our realities.</p>
    </div>
    <div>
      <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold/15 text-gold">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 20.5s-7.5-4.6-9.7-9.4C.7 7.4 2.4 4 5.8 3.4c2-.3 3.9.6 5 2.2a5.6 5.6 0 0 1 5-2.2c3.4.6 5.1 4 3.5 7.7-2.2 4.8-9.7 9.4-9.7 9.4Z"/></svg>
      </div>
      <h3 class="mt-4 font-bold text-ink">Actionable</h3>
      <p class="mt-2 text-sm leading-relaxed text-ink/60">We favour practical takeaways over alarm — what to actually do with the information.</p>
    </div>
  </div>
</section>

<section class="bleed bg-paper-warm py-16 md:py-24">
  <div class="container-xw grid grid-cols-1 items-center gap-12 md:grid-cols-2">
    <div class="overflow-hidden rounded-3xl shadow-xl shadow-ink/10 md:order-2">
      <img src="{rel('assets/images/covers/checkup-photo.jpg', cur)}" alt="" class="aspect-[4/3] w-full object-cover" />
    </div>
    <div class="md:order-1">
      <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-green">Our Story</p>
      <h2 class="mt-3 text-2xl font-bold text-ink md:text-3xl">Started from a simple observation.</h2>
      <p class="mt-5 text-base leading-relaxed text-ink/60">Too much health content online is either written for other doctors, or written to go viral — neither actually helps the person trying to decide whether that headache is worth a hospital visit, or what their genotype result actually means for their wedding plans.</p>
      <p class="mt-4 text-base leading-relaxed text-ink/60">Healthgists exists to close that gap: real medical understanding, translated into language that respects the reader's intelligence without assuming a medical degree — written with Nigeria's specific realities, food, and healthcare access in mind, not adapted from a US or UK publication after the fact.</p>
    </div>
  </div>
</section>

<section class="py-16 md:py-20">
  <div class="container-xw">
    <div class="max-w-lg">
      <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-green">How We Work</p>
      <h2 class="mt-3 text-2xl font-bold text-ink md:text-3xl">Every article follows the same process.</h2>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-3">
      <div class="rounded-2xl border border-ink/10 p-6">
        <span class="font-sans text-xs font-bold text-green">01</span>
        <h3 class="mt-3 font-bold text-ink">Grounded in evidence</h3>
        <p class="mt-2 text-sm leading-relaxed text-ink/60">Every claim traces back to established medical understanding — not a trending headline or a single study.</p>
      </div>
      <div class="rounded-2xl border border-ink/10 p-6">
        <span class="font-sans text-xs font-bold text-green">02</span>
        <h3 class="mt-3 font-bold text-ink">Written in plain language</h3>
        <p class="mt-2 text-sm leading-relaxed text-ink/60">If a sentence needs a medical dictionary to parse, it gets rewritten. Clarity is not optional.</p>
      </div>
      <div class="rounded-2xl border border-ink/10 p-6">
        <span class="font-sans text-xs font-bold text-green">03</span>
        <h3 class="mt-3 font-bold text-ink">Reviewed before publishing</h3>
        <p class="mt-2 text-sm leading-relaxed text-ink/60">Every piece passes through the Healthgists editorial desk before it goes live — nothing ships half-checked.</p>
      </div>
    </div>
  </div>
</section>

<section class="bleed bg-ink py-16 md:py-20">
  <div class="container-xw">
    <div class="flex flex-wrap items-end justify-between gap-4">
      <div class="max-w-lg">
        <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-white/50">What We Cover</p>
        <h2 class="mt-2 text-2xl font-bold text-white md:text-3xl">Four topics, one standard.</h2>
      </div>
      <a href="{rel('blog.html', cur)}" class="font-sans text-sm font-semibold text-white/80 hover:text-white">Browse all articles &rarr;</a>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
      {"".join(category_card(c, cur) for c in CATEGORIES)}
    </div>
  </div>
</section>

{newsletter_block()}
{footer(cur)}
{scripts(cur)}
</body>
</html>
"""
    write("about.html", html)


def build_contact():
    cur = ""
    html = head(
        f"Contact — {SITE_NAME}",
        "Get in touch with the Healthgists editorial team.",
        cur, "/contact.html",
    ) + f"""<body>
{header_nav(cur, "Contact")}

<section class="py-16 md:py-24">
  <div class="container-xw grid grid-cols-1 gap-12 md:grid-cols-2">
    <div>
      <h1 class="text-3xl font-extrabold text-ink md:text-4xl">Get in touch</h1>
      <p class="mt-4 max-w-md text-sm leading-relaxed text-ink/60">Story tip, correction, partnership enquiry, or just feedback — we read everything that comes through.</p>
      <div class="mt-8 space-y-5 text-sm">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green/10 text-green">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 5.5C4 4.67 4.67 4 5.5 4h13c.83 0 1.5.67 1.5 1.5v13c0 .83-.67 1.5-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/><path d="m4.5 6 7.5 6 7.5-6"/></svg>
          </div>
          <a href="mailto:hello@healthgists.com" class="font-medium text-ink hover:text-green">hello@healthgists.com</a>
        </div>
      </div>
    </div>
    <form class="rounded-3xl border border-ink/10 bg-white p-8">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div class="sm:col-span-1">
          <label class="text-xs font-semibold uppercase tracking-wide text-ink/50">Name</label>
          <input type="text" class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 text-sm outline-none focus:border-blue" />
        </div>
        <div class="sm:col-span-1">
          <label class="text-xs font-semibold uppercase tracking-wide text-ink/50">Email</label>
          <input type="email" class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 text-sm outline-none focus:border-blue" />
        </div>
        <div class="sm:col-span-2">
          <label class="text-xs font-semibold uppercase tracking-wide text-ink/50">Message</label>
          <textarea rows="5" class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 text-sm outline-none focus:border-blue"></textarea>
        </div>
      </div>
      <button type="submit" class="mt-6 inline-flex items-center justify-center rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5">Send Message</button>
    </form>
  </div>
</section>

{footer(cur)}
{scripts(cur)}
</body>
</html>
"""
    write("contact.html", html)


if __name__ == "__main__":
    build_index()
    build_blog()
    for p in POSTS:
        build_post(p)
    for c in CATEGORIES:
        build_category(c)
    build_about()
    build_contact()
    print("\nDone.")
