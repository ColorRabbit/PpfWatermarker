<?php

namespace PpfWatermarker\tests;

require_once '../../vendor/autoload.php';
use PdfWatermarker\Watermark;
use PdfWatermarker\Watermarker;
use setasign\Fpdi\Fpdi;

//Specify path to image. The image must have a 96 DPI resolution.
$watermark = new Watermark();
$watermark->setWatermarkFile('./2.jpg');
$fpdi = new Fpdi();
$watermarker = new Watermarker();
$watermarker->setTempPdf($fpdi);
$watermarker->setOriginalPdf('./test_14.pdf');
$watermarker->setNewPdf('./out_put.pdf');
$watermarker->setWatermark($watermark);
$watermarker->watermarkPdf();

