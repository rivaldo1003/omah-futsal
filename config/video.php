<?php

return [
    'highlight' => [
        'max_size' => 204800, // 200MB in KB
        'allowed_mimes' => [
            'video/mp4',
            'video/x-m4v',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-ms-wmv',
            'video/x-matroska',
            'video/x-flv',
        ],
        'allowed_extensions' => [
            'mp4',
            'm4v',
            'mov',
            'avi',
            'wmv',
            'mkv',
            'flv',
        ],
        'thumbnail' => [
            'max_size' => 5120, // 5MB
            'allowed_mimes' => [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/gif',
            ],
        ],
        'storage' => [
            'videos_path' => 'matches/highlights/videos',
            'thumbnails_path' => 'matches/highlights/thumbnails',
            'disk' => 'public',
        ],
    ],
];
