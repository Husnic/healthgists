#!/usr/bin/env python3
"""
Generates the site's cover illustrations as layered SVGs: a deep base
gradient, an "aurora" mesh of blurred color blobs, a subtle grain texture,
a refined double-stroke icon with soft drop shadow, and a small corner
wordmark for brand consistency across every cover.

Run: python3 bin/make_covers.py
"""
import os

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.join(ROOT, "assets/images/covers")

W, H = 1200, 630


def defs(uid, stops, blobs):
    stop_tags = "".join(
        f'<stop offset="{off}%" stop-color="{color}"/>' for off, color in stops
    )
    blob_tags = ""
    for i, (cx, cy, r, color, op) in enumerate(blobs):
        blob_tags += f'<circle cx="{cx}" cy="{cy}" r="{r}" fill="{color}" opacity="{op}" filter="url(#blur{uid})"/>'
    return f"""<defs>
    <linearGradient id="base{uid}" x1="0" y1="0" x2="1" y2="1">{stop_tags}</linearGradient>
    <filter id="blur{uid}" x="-50%" y="-50%" width="200%" height="200%">
      <feGaussianBlur stdDeviation="70"/>
    </filter>
    <filter id="shadow{uid}" x="-50%" y="-50%" width="200%" height="200%">
      <feDropShadow dx="0" dy="10" stdDeviation="14" flood-color="#000000" flood-opacity="0.25"/>
    </filter>
    <filter id="grain{uid}">
      <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" stitchTiles="stitch" result="noise"/>
      <feColorMatrix in="noise" type="matrix" values="0 0 0 0 1  0 0 0 0 1  0 0 0 0 1  0 0 0 0.03 0"/>
    </filter>
  </defs>
  <rect width="{W}" height="{H}" fill="url(#base{uid})"/>
  {blob_tags}
  <rect width="{W}" height="{H}" filter="url(#grain{uid})"/>"""


def watermark(uid):
    return f"""<g opacity="0.35" transform="translate(48,{H - 56})">
    <circle cx="10" cy="10" r="10" fill="#ffffff" opacity="0.9"/>
    <text x="28" y="15" font-family="Montserrat, sans-serif" font-size="15" font-weight="700" fill="#ffffff" letter-spacing="1">HEALTHGISTS</text>
  </g>"""


def svg(uid, stops, blobs, icon, viewbox_transform):
    return f"""<svg viewBox="0 0 {W} {H}" xmlns="http://www.w3.org/2000/svg">
  {defs(uid, stops, blobs)}
  <g filter="url(#shadow{uid})" transform="{viewbox_transform}">
    {icon}
  </g>
  {watermark(uid)}
</svg>
"""


def write(name, content):
    path = os.path.join(OUT, name)
    with open(path, "w") as f:
        f.write(content)
    print(f"wrote assets/images/covers/{name}")


# ── Preventive Care: stethoscope + heartbeat pulse ──────────────────────
ICON_CHECKUP = """
<g stroke="#ffffff" stroke-width="9" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path d="M70 10v70a70 70 0 0 0 140 0v-35" opacity="0.95"/>
  <circle cx="70" cy="10" r="16" opacity="0.95"/>
  <circle cx="210" cy="45" r="16" opacity="0.95"/>
  <circle cx="255" cy="185" r="40" opacity="0.95"/>
  <path d="M255 165v40M235 185h40" opacity="0.95"/>
  <path d="M-40 260h90l20-55 30 110 25-80 15 25h60" stroke-width="8" opacity="0.85"/>
</g>
"""
write("cover-checkup.svg", svg(
    "checkup",
    [(0, "#1e88e5"), (55, "#12578e"), (100, "#0a2a45")],
    [(1010, 90, 260, "#ffffff", 0.08), (120, 560, 220, "#f9a825", 0.14), (620, 480, 300, "#2e7d32", 0.10)],
    ICON_CHECKUP,
    "translate(430,200)",
))

# ── Genetics & Family Health: double helix ──────────────────────────────
ICON_GENOTYPE = """
<g stroke="#ffffff" stroke-width="9" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path d="M40 0C40 55 -20 55 -20 110s60 55 60 110-60 55-60 110M40 0c0 55 60 55 60 110s-60 55-60 110 60 55 60 110" opacity="0.95"/>
  <path d="M-18 28h116M-18 82h116M-18 138h116M-18 192h116M-18 248h116M-18 302h116" opacity="0.55" stroke-width="6"/>
  <circle cx="-20" cy="28" r="6" fill="#ffffff" stroke="none" opacity="0.9"/>
  <circle cx="98" cy="28" r="6" fill="#ffffff" stroke="none" opacity="0.9"/>
  <circle cx="100" cy="192" r="6" fill="#ffffff" stroke="none" opacity="0.9"/>
  <circle cx="-18" cy="192" r="6" fill="#ffffff" stroke="none" opacity="0.9"/>
</g>
"""
write("cover-genotype.svg", svg(
    "genotype",
    [(0, "#2e7d32"), (60, "#1b4d1e"), (100, "#0c220d")],
    [(1030, 500, 260, "#ffffff", 0.08), (150, 80, 200, "#1e88e5", 0.16), (980, 120, 180, "#f9a825", 0.10)],
    ICON_GENOTYPE,
    "translate(560,160)",
))

# ── Mental Health: heart + pulse ────────────────────────────────────────
ICON_MENTAL = """
<g stroke="#ffffff" stroke-width="9" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path d="M150 270c-95-42-158-116-158-179a94 94 0 0 1 158-68 94 94 0 0 1 158 68c0 63-63 137-158 179Z" opacity="0.95"/>
  <path d="M20 88h45l25-42 32 74 26-48 20 16h42" opacity="0.95"/>
</g>
"""
write("cover-mentalhealth.svg", svg(
    "mentalhealth",
    [(0, "#12578e"), (55, "#1e6b4f"), (100, "#2e7d32")],
    [(140, 520, 230, "#ffffff", 0.07), (1060, 110, 190, "#f9a825", 0.13), (700, 60, 220, "#1e88e5", 0.12)],
    ICON_MENTAL,
    "translate(450,175)",
))

# ── Nutrition & Wellness: leaf ───────────────────────────────────────────
ICON_NUTRITION = """
<g stroke="#ffffff" stroke-width="9" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path d="M135 40c65 0 110 52 110 118S200 280 135 280 25 226 25 158c0-33 13-60 32-82" opacity="0.95"/>
  <path d="M135 40c0-28 22-44 50-44-6 27-22 44-50 44Z" fill="#ffffff" stroke="none" opacity="0.95"/>
  <path d="M60 230C110 190 150 140 165 70" opacity="0.5" stroke-width="6"/>
</g>
"""
write("cover-nutrition.svg", svg(
    "nutrition",
    [(0, "#f9a825"), (55, "#c98a1e"), (100, "#2e7d32")],
    [(1030, 500, 240, "#ffffff", 0.09), (140, 110, 170, "#101b23", 0.08), (650, 480, 260, "#1e88e5", 0.10)],
    ICON_NUTRITION,
    "translate(470,150)",
))

# ── Hero / homepage banner: composite constellation of all four motifs ──
ICON_HERO = """
<g stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <g stroke-width="7" opacity="0.9" transform="translate(0,0)">
    <path d="M0 90h55l25-65 32 130 26-95 20 30h55"/>
  </g>
  <g stroke-width="6" opacity="0.35" transform="translate(-260,-70) scale(0.6)">
    <path d="M40 0C40 55 -20 55 -20 110s60 55 60 110M40 0c0 55 60 55 60 110s-60 55-60 110"/>
  </g>
  <g stroke-width="6" opacity="0.3" transform="translate(300,-40) scale(0.55)">
    <path d="M150 270c-95-42-158-116-158-179a94 94 0 0 1 158-68 94 94 0 0 1 158 68c0 63-63 137-158 179Z"/>
  </g>
</g>
"""
write("cover-hero.svg", svg(
    "hero",
    [(0, "#0a2a1c"), (45, "#12578e"), (100, "#1e88e5")],
    [(980, 500, 340, "#f9a825", 0.12), (140, 90, 220, "#ffffff", 0.07), (600, 560, 260, "#2e7d32", 0.14)],
    ICON_HERO,
    "translate(560,240)",
))

print("\nDone.")
