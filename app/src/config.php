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
 *****************/

return [
    /* Mode in which application runs:
        dev - show errors in browser
        prod - log errors to a log files */
    'env'        => 'dev',

    /* Relative path to app
        '/' = example.com/
        'app/' = example.com/app/ */
    'uri_base'   => '/',

    'http_schema' => 'http://',

    /* Get HTML as
        raw (comments, whitespaces etc)
        min  */
    'html_type'  => 'min',

    /* How to handle resulting pdf
        download
        show */
    'pdf_result' => 'show',

    /* Session continues until browser is closed or duration expires */
    'session'    => [
        'duration' => 32400 // 9 hours
    ],

    /*  Minimum and maximum number of stages in the treatment plan form. Min - 1, max - 10. */
    'stages'     => [
        'min' => 1,
        'max' => 5
    ],

    /* Directories */
    'dir'        => [
        /* Sqlite database */
        'sqlite'  => 'storage/sqlite/uti.sl3',

        /* Session's files */
        'session' => 'storage/sessions/',

        /* Templates
            view - php pages templates
            pdf - html template which would generate to pdf and merge after
        */
        'tpl'     => [
            'view' => 'storage/tpl/view/',
            'pdf'  => 'storage/tpl/pdf/',
        ],

        /* Application logs */
        'log'     => 'storage/logs/',

        /* Uploaded files resides here */
        'upload'  => 'storage/upload_dir/',

        /* Temporary files */
        'tmp'     => 'storage/tmp/',

        /* Pdf files
            in - pdf file (templates) ready to merge
            out - place for resulting pdf files.
        */
        'pdf'     => [
            'in'  => 'storage/pdf/',
            'out' => './../../pdf/',
        ],

        /* Path to system images */
        'img'     => [
            'doctors' => '../../doctors/',
            /*'common'  => 'storage/img/common/'*/
        ],
    ],
];

// OLD.
//return [
//    'app' => [
//        'env'        => 'dev',
//        'uri_base'   => '/',
//        'db'         => [
//            'sqlite' => 'storage/sqlite/uti.sl3'
//        ],
//        'html'       => 'min',
//        'result'     => 'show',
//        'stages'     => [
//            'min' => 1,
//            'max' => 5
//        ],
//        'session'    => [
//            'dir'      => 'storage/sessions/',
//            'duration' => 32400,
//        ],
//        'tpl'        => [
//            'view' => 'storage/tpl/view/',
//            'pdf'  => 'storage/tpl/pdf/',
//
//        ],
//        'log'        => [
//            'exceptions' => 'storage/logs/exceptions.log'
//        ],
//        'upload_dir' => 'storage/upload_dir/',
//        'tmp'        => 'storage/tmp/',
//        'pdf_in'     => 'storage/pdf/',
//        'pdf_out'    => './../../pdf/',
//        'img'        => [
//            'doctors' => '../../doctors/',
//            /*'common'  => 'storage/img/common/'*/
//        ],
//    ]
//];

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
