<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *  '<type>' => [
 *      'name' => '<Full name>',
 *      'path' => '<directory>'
 *  ]
 * ];
 * ```
 */

return [
    'default' => [
        'name' => 'Default',
        'path' => 'default'
    ],
];
