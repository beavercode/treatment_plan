<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

/**
 * todo <start>
 * Config file
 * vars values:
 *      required    boolean
 *      type        php types
 *      function    simple php functions like trim, htmlspecialchars with 3 params max, e.g.:
 *          ['htmlspecialchars', ENT_COMPAT, 'utf-8')
 *
 * todo Is common config file is good enough for validation?
 *      validation  validate var
 *          min     minimal number value
 *          max     maximal number value
 *          email   todo
 *          url     todo
 *          pattern todo
 *          pass    todo
 * todo <end>
 *
 *****************
 * App options *
 *****************
 *
 * app.stages - Minimum and maximum number of stages in the treatment plan
 *  - min: 1..10
 *  - max: 1..10
 *
 * app.env - Mode in which application runs
 *  - dev
 *  - prod
 *
 * app.uri_base - Relative path to app
 *  - '/' = example.com/
 *  - 'app/' = example.com/app/
 *
 * app.db.sqlite Place where sqlite db file situated
 *
 * app.html Get HTML as is (comments, whitespaces etc) or
 *  - raw
 *  - min
 *
 * app.session Session parameters
 *  - app.session.dir - place where are session files saved
 *  - app.session.duration - session's time to live, 32400 sec = 9 hours
 *
 * app.tpl Application templates are saved here
 *  - app.tpl.view - view templates
 *  - app.tpl.pdf - html templates for pdf generation
 *
 * app.result How to handle resulting pdf
 *  - download
 *  - show
 *
 * app.log Application log's dir.
 *
 * app.upload_dir Directory for uploaded files.
 *
 * app.tmp Directory for temporary files, e.g. templates converted to pdf.
 *
 * app.pdf_in Place for merge-ready pdf files.
 *
 * app.pdf_out Place for resulting pdf files.
 *
 * app.img.doctors Filesystem path Where doctors avatars are stored.
 *
 */

return [
    'app' => [
        'env'        => 'dev',
        'uri_base'   => '/',
        'db'         => [
            'sqlite' => 'storage/sqlite/uti.sl3'
        ],
        'html'       => 'min',
        'result'     => 'show',
        'stages'     => [
            'min' => 1,
            'max' => 5
        ],
        'session'    => [
            'dir'      => 'storage/sessions/',
            'duration' => 32400,
        ],
        'tpl'        => [
            'view' => 'storage/tpl/view/',
            'pdf'  => 'storage/tpl/pdf/',

        ],
        'log'        => [
            'exceptions' => 'storage/logs/exceptions.log'
        ],
        'upload_dir' => 'storage/upload_dir/',
        'tmp'        => 'storage/tmp/',
        'pdf_in'     => 'storage/pdf/',
        'pdf_out'    => './../../pdf/',
        'img'        => [
            'doctors' => '../../doctors/',
            /*'common'  => 'storage/img/common/'*/
        ],
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
