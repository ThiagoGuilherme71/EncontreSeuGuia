<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageResizer
{
    /**
     * Salva a imagem redimensionada (e opcionalmente um thumbnail) no disco public.
     * Retorna os paths relativos prontos para servir via /storage.
     *
     * Fotos de celular chegam com 5-12MB; em trilha a conexão é fraca,
     * então tudo vira JPEG comprimido com largura máxima controlada.
     *
     * @return array{path: string, thumb_path: ?string}
     */
    public static function save(UploadedFile $file, string $dir, int $maxWidth = 1600, ?int $thumbWidth = null): array
    {
        $source = self::createFromFile($file);

        $nome = Str::uuid()->toString();
        $path = "{$dir}/{$nome}.jpg";
        Storage::disk('public')->put($path, self::encode($source, $maxWidth));

        $thumbPath = null;
        if ($thumbWidth) {
            $thumbPath = "{$dir}/thumbs/{$nome}.jpg";
            Storage::disk('public')->put($thumbPath, self::encode($source, $thumbWidth));
        }

        imagedestroy($source);

        return [
            'path' => 'storage/' . $path,
            'thumb_path' => $thumbPath ? 'storage/' . $thumbPath : null,
        ];
    }

    /**
     * Cria o recurso GD a partir do upload, respeitando a orientação EXIF
     * (fotos de celular tiradas na vertical).
     */
    private static function createFromFile(UploadedFile $file): \GdImage
    {
        $img = imagecreatefromstring(file_get_contents($file->getRealPath()));

        if ($img === false) {
            abort(422, 'Não foi possível processar essa imagem.');
        }

        $exif = @exif_read_data($file->getRealPath());
        if (!empty($exif['Orientation'])) {
            $img = match ((int) $exif['Orientation']) {
                3 => imagerotate($img, 180, 0),
                6 => imagerotate($img, -90, 0),
                8 => imagerotate($img, 90, 0),
                default => $img,
            };
        }

        return $img;
    }

    /**
     * Redimensiona (se necessário) e codifica a imagem como JPEG (qualidade 80).
     */
    private static function encode(\GdImage $source, int $maxWidth): string
    {
        $width = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * $maxWidth / $width);
            $resized = imagescale($source, $maxWidth, $newHeight, IMG_BICUBIC);
        } else {
            $resized = $source;
        }

        ob_start();
        imagejpeg($resized, null, 80);
        $blob = ob_get_clean();

        if ($resized !== $source) {
            imagedestroy($resized);
        }

        return $blob;
    }
}
