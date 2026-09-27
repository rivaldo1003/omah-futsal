<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Removes a solid white/near-white background from team logos and returns a
 * transparent PNG. Uses a scanline flood-fill seeded from the image border so
 * only background connected to the edges is removed (white *inside* the logo
 * is preserved). Pure PHP GD — no external API/binary.
 */
class TeamLogoService
{
    /** Max dimension kept; logos render tiny, so downscaling saves memory/size. */
    private int $maxSize = 512;

    public function removeBackground(string $srcPath, int $threshold = 235): ?string
    {
        $info = @getimagesize($srcPath);
        if (!$info) {
            Log::warning('Team logo: unreadable image (' . $srcPath . ')');
            return null;
        }

        $src = $this->load($srcPath, $info[2]);
        if (!$src) {
            Log::warning('Team logo: unsupported format (type ' . $info[2] . ')');
            return null;
        }

        [$im, $w, $h] = $this->resize($src);
        imagedestroy($src);

        imagealphablending($im, false);
        imagesavealpha($im, true);

        $seen = str_repeat("\0", $w * $h);
        $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);

        $isWhite = function (int $x, int $y) use ($im, $threshold): bool {
            $rgb = imagecolorat($im, $x, $y);
            return (($rgb >> 16) & 0xFF) >= $threshold
                && (($rgb >> 8) & 0xFF) >= $threshold
                && ($rgb & 0xFF) >= $threshold;
        };

        $stack = [];
        for ($x = 0; $x < $w; $x++) {
            if ($isWhite($x, 0)) { $stack[] = [$x, 0]; }
            if ($isWhite($x, $h - 1)) { $stack[] = [$x, $h - 1]; }
        }
        for ($y = 0; $y < $h; $y++) {
            if ($isWhite(0, $y)) { $stack[] = [0, $y]; }
            if ($isWhite($w - 1, $y)) { $stack[] = [$w - 1, $y]; }
        }

        while ($stack) {
            [$x, $y] = array_pop($stack);
            if ($y < 0 || $y >= $h || $x < 0 || $x >= $w) {
                continue;
            }
            if ($seen[$y * $w + $x] !== "\0" || !$isWhite($x, $y)) {
                continue;
            }

            $x1 = $x;
            while ($x1 > 0 && $seen[$y * $w + ($x1 - 1)] === "\0" && $isWhite($x1 - 1, $y)) {
                $x1--;
            }
            $x2 = $x;
            while ($x2 < $w - 1 && $seen[$y * $w + ($x2 + 1)] === "\0" && $isWhite($x2 + 1, $y)) {
                $x2++;
            }

            for ($i = $x1; $i <= $x2; $i++) {
                $seen[$y * $w + $i] = "\1";
                imagesetpixel($im, $i, $y, $transparent);
            }

            foreach ([$y - 1, $y + 1] as $ny) {
                if ($ny < 0 || $ny >= $h) {
                    continue;
                }
                for ($i = $x1; $i <= $x2; $i++) {
                    if ($seen[$ny * $w + $i] === "\0" && $isWhite($i, $ny)) {
                        $stack[] = [$i, $ny];
                    }
                }
            }
        }

        ob_start();
        imagepng($im);
        $png = ob_get_clean();
        imagedestroy($im);

        return $png ?: null;
    }

    /** @return array{0: \GdImage, 1: int, 2: int} */
    private function resize($src): array
    {
        $w = imagesx($src);
        $h = imagesy($src);

        $scale = min(1, $this->maxSize / max($w, $h));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        return [$dst, $nw, $nh];
    }

    private function load(string $path, int $type)
    {
        return match ($type) {
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_GIF  => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default        => null,
        };
    }
}