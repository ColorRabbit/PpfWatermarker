<?php

namespace PdfWatermarker\tests;

require_once '../vendor/autoload.php';

use PdfWatermarker\Watermarker;

//Specify path to image. The image must have a 96 DPI resolution.
$watermarker = new Watermarker();
$watermarker->setWatermarkFile('./2.jpg')
    ->setOriginalPdf('./test_14.pdf')
    ->setNewPdf('./out_put.pdf')
    ->watermarkPdf();

