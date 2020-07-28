<?php

return [
    'components' => [
        'seo' => [
            'name' => 'SEO Data',
            'description' => 'Allows access to SEO information. Can be rendered for meta tags.',
            'properties' => [
                'include_open_graph' => [
                    'title' => 'Include Open Graph tags'
                ],
                'include_twitter' => [
                    'title' => 'Include Twitter tags'
                ],
                'include_json_ld' => [
                    'title' => 'Include JSON-LD'
                ]
            ]
        ]
    ],
    'fields' => [
        'seo_keywords' => 'SEO Keywords',
        'seo_canonical_url' => 'Canonical URL',
        'seo_redirect_url' => 'Redirect URL',
        'robots_index' => 'Robots index',
        'robots_follow' => 'Robots follow'
    ],
    'plugin' => [
        'name' => 'SEO Tweaker',
        'description' => 'SEO Extensions'
    ]
];
