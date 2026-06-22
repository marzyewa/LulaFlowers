from pathlib import Path
from random import Random

from PIL import Image, ImageDraw, ImageFilter


OUT = Path(__file__).resolve().parents[1] / "public" / "images" / "flowers"


def gradient(size, top, bottom):
    image = Image.new("RGB", size)
    draw = ImageDraw.Draw(image)
    for y in range(size[1]):
        t = y / max(size[1] - 1, 1)
        color = tuple(int(top[i] * (1 - t) + bottom[i] * t) for i in range(3))
        draw.line((0, y, size[0], y), fill=color)
    return image


def leaf(draw, x, y, scale, color, angle=0):
    layer = Image.new("RGBA", draw._image.size, (0, 0, 0, 0))
    d = ImageDraw.Draw(layer)
    d.ellipse((x - 28 * scale, y - 70 * scale, x + 28 * scale, y + 70 * scale), fill=color)
    layer = layer.rotate(angle, center=(x, y), resample=Image.Resampling.BICUBIC)
    draw._image.alpha_composite(layer)


def flower(draw, x, y, radius, petal, center, petals=8):
    import math

    for i in range(petals):
        a = math.tau * i / petals
        px = x + math.cos(a) * radius * 0.62
        py = y + math.sin(a) * radius * 0.62
        draw.ellipse(
            (px - radius * 0.48, py - radius * 0.7, px + radius * 0.48, py + radius * 0.7),
            fill=petal,
        )
    draw.ellipse((x - radius * 0.32, y - radius * 0.32, x + radius * 0.32, y + radius * 0.32), fill=center)


def create(name, size, palette, seed, hero=False):
    rng = Random(seed)
    base = gradient(size, palette["top"], palette["bottom"]).convert("RGBA")

    glow = Image.new("RGBA", size, (0, 0, 0, 0))
    gd = ImageDraw.Draw(glow)
    for _ in range(12):
        x, y = rng.randrange(size[0]), rng.randrange(size[1])
        r = rng.randrange(100, 360)
        gd.ellipse((x - r, y - r, x + r, y + r), fill=(*palette["glow"], rng.randrange(18, 48)))
    glow = glow.filter(ImageFilter.GaussianBlur(70))
    base.alpha_composite(glow)

    stems = Image.new("RGBA", size, (0, 0, 0, 0))
    sd = ImageDraw.Draw(stems)
    count = 24 if hero else 16
    x_start = int(size[0] * (0.48 if hero else 0.08))

    for _ in range(count):
        x = rng.randrange(x_start, size[0] - 60)
        y = rng.randrange(int(size[1] * 0.18), int(size[1] * 0.78))
        bottom_x = x + rng.randrange(-100, 100)
        sd.line((bottom_x, size[1] + 20, x, y), fill=palette["stem"], width=rng.randrange(7, 15))
        leaf(sd, int((x + bottom_x) / 2), int((y + size[1]) / 2), rng.uniform(0.7, 1.35), palette["leaf"], rng.randrange(-70, 70))
        petal = rng.choice(palette["petals"])
        flower(sd, x, y, rng.randrange(34, 76), petal, palette["center"], rng.choice([6, 8, 10]))

    stems = stems.filter(ImageFilter.GaussianBlur(0.35))
    base.alpha_composite(stems)

    grain = Image.effect_noise(size, 16).convert("L")
    grain_rgba = Image.merge("RGBA", (grain, grain, grain, Image.new("L", size, 12)))
    base = Image.alpha_composite(base, grain_rgba)
    base.convert("RGB").save(OUT / name, quality=90, optimize=True)


def main():
    OUT.mkdir(parents=True, exist_ok=True)
    palettes = {
        "hero.jpg": dict(top=(238, 241, 232), bottom=(212, 225, 211), glow=(255, 239, 232), stem=(57, 91, 70, 220), leaf=(92, 132, 99, 210), petals=[(231, 173, 181, 235), (244, 218, 210, 240), (247, 240, 221, 245)], center=(203, 150, 91, 245)),
        "care.jpg": dict(top=(234, 239, 224), bottom=(185, 208, 181), glow=(239, 221, 181), stem=(45, 82, 61, 230), leaf=(70, 126, 83, 225), petals=[(248, 240, 210, 235), (220, 192, 132, 235)], center=(139, 102, 57, 245)),
        "houseplants.jpg": dict(top=(224, 235, 222), bottom=(139, 177, 145), glow=(255, 238, 206), stem=(37, 78, 55, 235), leaf=(52, 113, 68, 230), petals=[(211, 227, 201, 225), (245, 231, 193, 220)], center=(104, 94, 53, 240)),
        "bouquet.jpg": dict(top=(247, 233, 232), bottom=(221, 195, 199), glow=(255, 245, 222), stem=(65, 99, 73, 220), leaf=(102, 139, 103, 210), petals=[(183, 92, 111, 235), (230, 153, 166, 240), (249, 219, 212, 245)], center=(192, 139, 74, 245)),
        "seasonal.jpg": dict(top=(245, 238, 211), bottom=(184, 214, 177), glow=(255, 220, 178), stem=(57, 103, 66, 230), leaf=(82, 143, 83, 220), petals=[(235, 109, 105, 240), (246, 181, 111, 240), (248, 224, 168, 245)], center=(142, 98, 44, 245)),
        "floristry.jpg": dict(top=(235, 224, 231), bottom=(191, 176, 196), glow=(255, 229, 208), stem=(55, 82, 68, 230), leaf=(88, 119, 91, 220), petals=[(115, 62, 93, 240), (181, 116, 149, 240), (232, 192, 195, 245)], center=(194, 145, 78, 245)),
        "placeholder.jpg": dict(top=(239, 239, 226), bottom=(213, 222, 205), glow=(246, 214, 216), stem=(59, 91, 70, 225), leaf=(94, 130, 96, 215), petals=[(224, 163, 173, 235), (244, 216, 207, 240)], center=(190, 142, 75, 245)),
    }

    for index, (name, palette) in enumerate(palettes.items()):
        create(name, (1600, 1900) if name == "hero.jpg" else (1600, 1000), palette, 410 + index, name == "hero.jpg")


if __name__ == "__main__":
    main()
