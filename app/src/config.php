<?php
/**
 * Config file
 * vars values:
 *      required    boolean
 *      type        php types
 *      function    simple php functions like trim, htmlspecialchars with 3 params max, e.g.:
 *          ['htmlspecialchars', ENT_COMPAT, 'utf-8')
 *
 *      validation  validate var
 *          min     minimal number value
 *          max     maximal number value
 *          email   todo
 *          url     todo
 *          pattern todo
 *          pass    todo
 *
 *****************
 * App options *
 *****************
 *
 * app.env Mode in which application runs
 *  - dev
 *  - prod
 *
 * app.html Get HTML as is (comments, whitespaces etc) or
 *  - raw
 *  - min
 */

return [
    'app' => [
        'stages'   => [
            'min' => 1,
            'max' => 5
        ],
        'env'      => 'dev',
        'uri_base' => '/',
        'html'     => 'min',
        'session'  => [
            'dir'      => 'storage/sessions/',
            'duration' => 32400
        ],
        'tpl'      => [
            'pdf'  => 'storage/tpl/pdf/',
            'view' => 'storage/tpl/view/'
        ],
        'log'      => 'storage/logs/',
        'docx'     => 'storage/docx/',
        'pdf_in'   => 'storage/pdf/',
        'pdf_out'  => './../../pdf/',
        'img'      => [
            'doctors' => '../../doctors/',
            /*'common'  => 'storage/img/common/'*/
        ],
        'tmp'      => 'storage/tmp/'
    ]
];

/*    'db'   => [
        'host' => 'localhost',
        'user' => 'gb_test_user',
        'pass' => 'w5wEWOz4wuLe',
        'db'   => 'gb_test'
    ],
    'vars' => [
        'login' => [
            'login' => [
                'required'   => true,
                'function'   => [
                    ['trim'],
                    ['htmlspecialchars', ENT_COMPAT, 'utf-8']
                ],
                'validation' => [
                    'min'     => 3,
                    'max'     => 15
                ]
            ],
            'password' => [
                'required' => true,
                'validation' => [
                    'min'     => 3,
                    'max'     => 15,
                    'pass'   => true
                ]
            ]
        ]
    ]
];*/

/*  full example
        'form_article' => [
            'name',
            'excerpt',
            'text'
        ],
        'form_comment' => [
            'name'    => [
                'required'   => true,
                'type'       => 'string',
                'function'   => ['trim'],
                'validation' => [
                    'min'     => 3,
                    'max'     => 25,
                    'email'   => false,
                    'url'     => false,
                    'pattern' => false

                ]
            ],
            'message' => [
                'required'   => true,
                'type'       => 'string',
                'function'   => ['trim'],
                'validation' => [
                    'min' => 5,
                    'pass' => false
                ]
            ]
        ]*/
