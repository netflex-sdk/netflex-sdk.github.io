<?php

use Doctum\Doctum;
use Doctum\RemoteRepository\GitHubRemoteRepository;

use Symfony\Component\Finder\Finder;

$dir = __DIR__ . '/vendor/netflex';

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->notPath('/(.+)?[tT]ests?(\/.+)?/')
    ->notPath('/(.+)?[mM]ocks?(\/.+)?/')
    ->notPath('tests')
    ->notPath('Tests')
    ->notPath('Mocks')
    ->notPath('Laravel')
    ->notPath('Illuminate')
    ->notName('_ide_helper.php')
    ->filter(function (SplFileInfo $file) use ($dir) {
        return strpos($file->getPathname(), $dir) === 0;
    })
    ->in($dir);

return new Doctum($iterator, [
    'title'                => 'Netflex SDK',
    'source_dir'           => dirname($dir) . '/',
    'build_dir'            => __DIR__ . '/docs/api/',
    'cache_dir'            => __DIR__ . '/cache',
    'footer_link'          => [
        'href'        => 'https://www.apility.no',
        'rel'         => 'noreferrer noopener',
        'target'      => '_blank',
        'before_text' => 'Copyright ',
        'link_text'   => 'Apility AS', // Required if the href key is set
        'after_text'  => '© ' . date('Y', time()),
    ],
]);
