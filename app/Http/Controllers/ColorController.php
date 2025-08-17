<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use League\ColorExtractor\Palette;
use League\ColorExtractor\ColorExtractor;
class ColorController extends Controller
{
    public function update(Request $request, Color $color)
    {
        $path = public_path('12.png');

        if (!file_exists($path)) {
            return response()->json(['error' => "Image not found at $path"]);
        }

        // Force GD to load as PNG
        $gd = @imagecreatefrompng($path);

        if (!$gd) {
            return response()->json(['error' => 'Failed to open image with GD']);
        }

        // Create palette from GD resource
        $palette = Palette::fromGD($gd);
        $extractor = new ColorExtractor($palette);

        // Top 1 dominant color
        $colors = $extractor->extract(1);

        if (empty($colors)) {
            return response()->json(['error' => 'No colors extracted']);
        }

        $hex = sprintf("#%06X", $colors[0]);

        $color->button_color = $hex;
        $color->text_color = $this->getContrastColor($hex);
        $color->save();

    
    }

    private function getContrastColor($hexColor): string
    {
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));

        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;

        return $brightness > 128 ? '#000000' : '#FFFFFF';
    }
}
