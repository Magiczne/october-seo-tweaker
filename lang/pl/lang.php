<?php

return [
    'components' => [
        'seo' => [
            'name' => 'Dane SEO',
            'description' => 'Pozwala na dostęp do danych SEO. Może być wyświetlony.',
            'properties' => [
                'include_open_graph' => [
                    'title' => 'Dołącz tagi Open Graph'
                ],
                'include_twitter' => [
                    'title' => 'Dołącz tagi Twitter'
                ],
                'include_json_ld' => [
                    'title' => 'Dołącz JSON-LD'
                ]
            ]
        ]
    ],
    'fields' => [
        'seo_keywords' => 'SEO Słowa kluczowe',
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
