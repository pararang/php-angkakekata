<?php

use \mageekguy\atoum\report\fields\runner\coverage;

$coverageHtmlField = new coverage\html(
    'Konversi Angka ke Kata',
    '/path/to/destination/directory'
);
$coverageHtmlField->setRootUrl('http://url/of/web/site');
$coverageTreemapField = new coverage\treemap(
    'Your project name',
    '/path/to/destination/directory'
);

$coverageTreemapField
    ->setTreemapUrl('http://url/of/treemap')
    ->setHtmlReportBaseUrl($coverageHtmlField->getRootUrl());

$script
    ->addDefaultReport()
    ->addField($coverageHtmlField)
    ->addField($coverageTreemapField);
