<?php
/**
 * Created by MenaPRO.
 * User: silvia
 * Date: 13/05/2016
 * Time: 10:56
 */

return [
    'sourcePath' => __DIR__.DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
    'languages' => ['es-ES','en-US','de-DE','fr-FR'], //Add languages to the array for the language files to be generated.
    'translator' => 'Yii::t',
    'sort' => false,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/vendor',
    ],
    'format' => 'php',
    'messagePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'messages',
    'overwrite' => true,
];