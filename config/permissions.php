<?php

return [
    'roles' => [
        'admin' => ['*'],
        'editor' => [
            'articles.view', 'articles.create', 'articles.update', 'articles.delete', 'articles.publish',
            'categories.manage', 'tags.manage', 'banners.manage', 'comments.manage',
        ],
        'author' => [
            'articles.view', 'articles.create', 'articles.update.own', 'articles.delete.own',
        ],
        'moderator' => [
            'comments.manage',
        ],
        'reader' => [],
    ],
];