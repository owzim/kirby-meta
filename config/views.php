<?php

use FabianMichael\Meta\Sitemap;
use Kirby\Cms\Url;

return [
    [
        'pattern' => 'meta',
        'action'  => function () {

            $pages = [];

            foreach (site()->index() as $page) {
                $meta = $page->meta();

                $og_image = $meta->og_image();
                if ($og_image !== null) {
                    if ($og_image->exists() === true) {
                        // Only resize, if given image does actually exist
                        // and is accessible
                        $og_image_url = $og_image->crop(192, 101)->url();
                    } else {
                        // Probably a virtual file
                        $og_image_url = $og_image->url();
                    }
                } else {
                    $og_image_url = null;
                }

                $pages[] = [
                    'title' => $page->title()->value(),
                    'meta_title' => $meta->get('meta_title')->value(),
                    'icon'  => $page->blueprint()->icon(),
                    'is_indexible' => Sitemap::isPageIndexible($page),
                    'status'  => $page->status(),
                    'id' => $page->id(),
                    'url' => $page->url(),
                    'shortUrl' => Url::short($page->url()),
                    'template' => $page->template()->name(),
                    'panelUrl' => $page->panelUrl(),
                    'meta_description' => $meta->meta_description()->value(),
                    'robots' => $meta->robots(),
                    'og_title' => $meta->get('og_title')->value(),
                    'og_description' => $meta->get('og_description')->value(),
                    'og_image_url' => $og_image_url,
                    'og_image_alt' => $og_image?->alt()->value(),
                ];
            }

            return [
                'component' => 'k-meta-view',
                'props' => [
                    'pages' => $pages,
                ]
            ];
        }
    ]
];
