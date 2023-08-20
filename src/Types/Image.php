<?php

namespace Cabinet\Types;

use Cabinet\Types\Concerns\StringableAsSlug;
use Cabinet\Types\Concerns\UsesDefaultIcon;

class Image implements \Cabinet\FileType
{
    use StringableAsSlug;
    use UsesDefaultIcon;

    public function name(): string
    {
        return __('cabinet::files.image');
    }

    public function slug(): string
    {
        return 'image';
    }

    public static function supportedMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'image/webp',
            'image/bmp',
            'image/tiff',
            'image/x-icon',
        ];
    }
}
