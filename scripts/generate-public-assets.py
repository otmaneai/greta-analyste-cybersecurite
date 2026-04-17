#!/usr/bin/env python3
from __future__ import annotations

from pathlib import Path
from textwrap import wrap

from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parents[1]
PREVIEW_ASSETS = ROOT / "preview" / "assets"
DOCS_ASSETS = ROOT / "docs" / "assets"

BG = "#0e1116"
SURFACE = "#141922"
SURFACE_SOFT = "#1a2130"
TEXT = "#edf2f7"
MUTED = "#98a3b3"
ACCENT = "#31c48d"
WARM = "#f59e0b"
ROSE = "#fb7185"
BORDER = (255, 255, 255, 28)


def font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont | ImageFont.ImageFont:
	candidates = [
		("/System/Library/Fonts/Supplemental/Arial Bold.ttf" if bold else "/System/Library/Fonts/Supplemental/Arial.ttf"),
		("/System/Library/Fonts/Supplemental/Helvetica.ttc"),
	]

	for candidate in candidates:
		try:
			return ImageFont.truetype(candidate, size=size)
		except OSError:
			continue

	return ImageFont.load_default()


def new_canvas(width: int, height: int) -> tuple[Image.Image, ImageDraw.ImageDraw]:
	image = Image.new("RGB", (width, height), BG)
	draw = ImageDraw.Draw(image)
	for y in range(0, height, 32):
		draw.line((0, y, width, y), fill=(255, 255, 255, 10))
	for x in range(0, width, 32):
		draw.line((x, 0, x, height), fill=(255, 255, 255, 10))
	draw.rectangle((0, 0, width - 1, height - 1), outline=(255, 255, 255, 16))
	return image, draw


def rounded_box(draw: ImageDraw.ImageDraw, box: tuple[int, int, int, int], fill: str, outline=BORDER, radius: int = 24) -> None:
	draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=1)


def draw_text_block(draw: ImageDraw.ImageDraw, x: int, y: int, text: str, max_chars: int, fill: str, body_font, line_gap: int = 10) -> int:
	lines = []
	for paragraph in text.split("\n"):
		lines.extend(wrap(paragraph, width=max_chars) or [""])
	total_height = 0
	for line in lines:
		draw.text((x, y + total_height), line, font=body_font, fill=fill)
		total_height += body_font.size + line_gap
	return total_height


def draw_tag(draw: ImageDraw.ImageDraw, x: int, y: int, label: str, fill_color: str, text_color: str = BG) -> int:
	tag_font = font(22, bold=True)
	text_box = draw.textbbox((0, 0), label, font=tag_font)
	width = (text_box[2] - text_box[0]) + 34
	height = 42
	draw.rounded_rectangle((x, y, x + width, y + height), radius=21, fill=fill_color)
	draw.text((x + 17, y + 9), label, font=tag_font, fill=text_color)
	return width


def cover_thumb(image_name: str, size: tuple[int, int]) -> Image.Image:
	image = Image.open(PREVIEW_ASSETS / image_name).convert("RGB")
	image.thumbnail(size)
	canvas = Image.new("RGB", size, SURFACE)
	offset = ((size[0] - image.width) // 2, (size[1] - image.height) // 2)
	canvas.paste(image, offset)
	return canvas


def build_wordmark() -> None:
	image, draw = new_canvas(1600, 500)
	rounded_box(draw, (56, 56, 1544, 444), SURFACE)
	draw.text((96, 108), "GRETA", font=font(82, bold=True), fill=ACCENT)
	draw.text((96, 208), "ANALYSTE", font=font(118, bold=True), fill=TEXT)
	draw.text((96, 332), "CYBERSÉCURITÉ", font=font(118, bold=True), fill=TEXT)
	draw_text_block(
		draw,
		1030,
		132,
		"Plateforme d'apprentissage premium\net projet vitrine appliqué",
		18,
		MUTED,
		font(36),
		12,
	)
	image.save(DOCS_ASSETS / "wordmark.png")


def build_avatar() -> None:
	image, draw = new_canvas(1024, 1024)
	rounded_box(draw, (76, 76, 948, 948), SURFACE)
	draw.rounded_rectangle((148, 148, 876, 876), radius=72, fill=SURFACE_SOFT, outline=(255, 255, 255, 20))
	draw.text((212, 252), "GA", font=font(300, bold=True), fill=TEXT)
	draw.text((212, 596), "CYBER", font=font(122, bold=True), fill=ACCENT)
	draw.text((216, 742), "STACK", font=font(86, bold=True), fill=MUTED)
	image.save(DOCS_ASSETS / "avatar.png")


def build_repo_social() -> None:
	image, draw = new_canvas(1200, 630)
	rounded_box(draw, (38, 38, 1162, 592), SURFACE)
	draw.text((74, 82), "GRETA ANALYSTE", font=font(56, bold=True), fill=TEXT)
	draw.text((74, 146), "CYBERSÉCURITÉ", font=font(68, bold=True), fill=TEXT)
	draw_text_block(
		draw,
		74,
		246,
		"Plateforme d'apprentissage premium issue du corpus GRETA, construite avec le stack étudié pendant la formation.",
		30,
		MUTED,
		font(26),
		8,
	)
	tag_x = 74
	for label, color in (
		("WordPress", ACCENT),
		("PHP", WARM),
		("MariaDB", ROSE),
		("Apache2", ACCENT),
		("Tailwind", WARM),
		("Alpine.js", ROSE),
	):
		tag_x += draw_tag(draw, tag_x, 334, label, color) + 12

	draw.text((74, 420), "29 PDF  |  233 pages  |  11 learning paths", font=font(34, bold=True), fill=TEXT)
	draw_text_block(draw, 74, 478, "Support structuré de cours + preuve de pratique technique.", 34, MUTED, font(28), 8)

	cards = [
		("php-path.png", (746, 98)),
		("wordpress-path.png", (904, 66)),
		("apache-path.png", (866, 316)),
	]
	for image_name, (x, y) in cards:
		card = cover_thumb(image_name, (250, 200))
		draw.rounded_rectangle((x - 8, y - 8, x + 258, y + 208), radius=18, fill=SURFACE_SOFT, outline=(255, 255, 255, 32))
		image.paste(card, (x, y))

	image.save(DOCS_ASSETS / "repo-social.png")


def build_portfolio_cover() -> None:
	image, draw = new_canvas(1600, 900)
	rounded_box(draw, (48, 48, 1552, 852), SURFACE)
	draw.text((92, 94), "GRETA ANALYSTE CYBERSÉCURITÉ", font=font(76, bold=True), fill=TEXT)
	draw_text_block(
		draw,
		92,
		198,
		"Un projet portfolio qui transforme un corpus GRETA réel en plateforme WordPress premium, structurée autour de learning paths, ressources, modules, leçons et narration de stack appliquée.",
		38,
		MUTED,
		font(34),
		10,
	)

	metrics_y = 410
	for index, (label, value, color) in enumerate(
		(
			("Learning paths", "11", ACCENT),
			("Resources", "29", WARM),
			("Corpus pages", "233", ROSE),
		)
	):
		left = 92 + index * 214
		rounded_box(draw, (left, metrics_y, left + 182, metrics_y + 126), SURFACE_SOFT, radius=18)
		draw.text((left + 22, metrics_y + 18), value, font=font(48, bold=True), fill=color)
		draw.text((left + 22, metrics_y + 78), label, font=font(24), fill=MUTED)

	panel_x = 856
	panel_y = 126
	panel_specs = [
		("Home", "wordpress-path.png"),
		("PHP Path", "php-path.png"),
		("Module", "apache-path.png"),
		("Resource", "mariadb-path.png"),
	]
	for idx, (label, image_name) in enumerate(panel_specs):
		x = panel_x + (idx % 2) * 314
		y = panel_y + (idx // 2) * 256
		rounded_box(draw, (x, y, x + 278, y + 220), SURFACE_SOFT, radius=18)
		card = cover_thumb(image_name, (246, 150))
		image.paste(card, (x + 16, y + 18))
		draw.text((x + 16, y + 182), label, font=font(28, bold=True), fill=TEXT)
		draw.text((x + 16, y + 214), "Preview state", font=font(20), fill=MUTED)

	tag_x = 92
	for label, color in (
		("WordPress", ACCENT),
		("PHP", WARM),
		("MariaDB", ROSE),
		("Apache2", ACCENT),
		("Tailwind CSS", WARM),
		("Alpine.js", ROSE),
		("HTML", ACCENT),
	):
		tag_x += draw_tag(draw, tag_x, 624, label, color) + 12

	draw_text_block(
		draw,
		92,
		704,
		"GitHub comme source de vérité. Railway comme cible de déploiement. Thème custom + plugin custom + inventaire JSON comme socle produit.",
		48,
		MUTED,
		font(30),
		10,
	)
	image.save(DOCS_ASSETS / "portfolio-cover.png")


def build_preview_grid() -> None:
	image, draw = new_canvas(1400, 860)
	rounded_box(draw, (34, 34, 1366, 826), SURFACE)
	draw.text((66, 56), "Preview Entry Points", font=font(56, bold=True), fill=TEXT)
	draw.text((66, 126), "Vues prêtes à ouvrir depuis le repo public.", font=font(28), fill=MUTED)
	cells = [
		("Homepage", "wordpress-path.png", "index.html"),
		("PHP Path", "php-path.png", "php-path.html"),
		("Module", "apache-path.png", "php-module.html"),
		("Resource", "mariadb-path.png", "parcinfo-resource.html"),
	]
	for idx, (title, image_name, link) in enumerate(cells):
		x = 66 + (idx % 2) * 640
		y = 192 + (idx // 2) * 300
		rounded_box(draw, (x, y, x + 604, y + 248), SURFACE_SOFT, radius=18)
		card = cover_thumb(image_name, (238, 180))
		image.paste(card, (x + 18, y + 18))
		draw.text((x + 280, y + 26), title, font=font(34, bold=True), fill=TEXT)
		draw_text_block(draw, x + 280, y + 78, link, 28, ACCENT, font(24), 8)
		draw_text_block(draw, x + 280, y + 128, "Point d'entrée portfolio pour expliquer la structure, la progression et la stack appliquée.", 30, MUTED, font(22), 6)
	image.save(DOCS_ASSETS / "preview-grid.png")


def build_architecture_svg() -> None:
	svg = f"""<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="900" viewBox="0 0 1600 900" fill="none">
  <rect width="1600" height="900" fill="{BG}"/>
  <g opacity="0.12" stroke="white">
    {"".join(f'<line x1="{x}" y1="0" x2="{x}" y2="900"/>' for x in range(0, 1601, 48))}
    {"".join(f'<line x1="0" y1="{y}" x2="1600" y2="{y}"/>' for y in range(0, 901, 48))}
  </g>
  <text x="90" y="110" fill="{TEXT}" font-family="Arial, Helvetica, sans-serif" font-size="58" font-weight="700">Architecture Portfolio</text>
  <text x="90" y="158" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="28">GitHub comme source de vérité, Railway comme cible de déploiement, WordPress comme produit.</text>

  <rect x="90" y="260" width="260" height="150" rx="18" fill="{SURFACE}" stroke="rgba(255,255,255,0.18)"/>
  <text x="126" y="324" fill="{TEXT}" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="700">GitHub</text>
  <text x="126" y="372" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">repo public</text>

  <rect x="430" y="260" width="320" height="150" rx="18" fill="{SURFACE}" stroke="rgba(255,255,255,0.18)"/>
  <text x="466" y="324" fill="{TEXT}" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="700">Railway</text>
  <text x="466" y="372" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">build + deploy + vars</text>

  <rect x="850" y="220" width="360" height="230" rx="18" fill="{SURFACE}" stroke="rgba(255,255,255,0.18)"/>
  <text x="886" y="290" fill="{TEXT}" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="700">WordPress / PHP / Apache2</text>
  <text x="886" y="340" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">thème custom</text>
  <text x="886" y="376" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">plugin custom</text>
  <text x="886" y="412" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">inventory JSON + uploads PDF</text>

  <rect x="850" y="560" width="360" height="170" rx="18" fill="{SURFACE}" stroke="rgba(255,255,255,0.18)"/>
  <text x="886" y="632" fill="{TEXT}" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="700">MariaDB</text>
  <text x="886" y="680" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">service séparé Railway</text>

  <rect x="1280" y="260" width="230" height="150" rx="18" fill="{SURFACE}" stroke="rgba(255,255,255,0.18)"/>
  <text x="1316" y="324" fill="{TEXT}" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="700">Reviewers</text>
  <text x="1316" y="372" fill="{MUTED}" font-family="Arial, Helvetica, sans-serif" font-size="24">recruteurs / jury</text>

  <path d="M350 335H430" stroke="{ACCENT}" stroke-width="8" stroke-linecap="round"/>
  <path d="M750 335H850" stroke="{ACCENT}" stroke-width="8" stroke-linecap="round"/>
  <path d="M1030 450V560" stroke="{WARM}" stroke-width="8" stroke-linecap="round"/>
  <path d="M1210 335H1280" stroke="{ROSE}" stroke-width="8" stroke-linecap="round"/>

  <circle cx="390" cy="335" r="10" fill="{ACCENT}"/>
  <circle cx="800" cy="335" r="10" fill="{ACCENT}"/>
  <circle cx="1030" cy="505" r="10" fill="{WARM}"/>
  <circle cx="1245" cy="335" r="10" fill="{ROSE}"/>

  <text x="370" y="304" fill="{ACCENT}" font-family="Arial, Helvetica, sans-serif" font-size="22">push</text>
  <text x="782" y="304" fill="{ACCENT}" font-family="Arial, Helvetica, sans-serif" font-size="22">deploy</text>
  <text x="1050" y="516" fill="{WARM}" font-family="Arial, Helvetica, sans-serif" font-size="22">db link</text>
  <text x="1216" y="304" fill="{ROSE}" font-family="Arial, Helvetica, sans-serif" font-size="22">portfolio proof</text>
</svg>
"""
	(DOCS_ASSETS / "architecture.svg").write_text(svg, encoding="utf-8")


def main() -> None:
	DOCS_ASSETS.mkdir(parents=True, exist_ok=True)
	build_wordmark()
	build_avatar()
	build_repo_social()
	build_portfolio_cover()
	build_preview_grid()
	build_architecture_svg()
	print("Generated public assets in", DOCS_ASSETS)


if __name__ == "__main__":
	main()
