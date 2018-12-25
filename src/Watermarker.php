<?php

namespace PdfWatermarker;

class Watermarker
{
    /**
     * @var
     */
    private $newPdf;

    /**
     * @var
     */
    private $originalPdf;

    /**
     * @var
     */
    private $tempPdf;

    /**
     * @var
     */
    private $watermark;

    /**
     * @var string
     */
    private $imagePositionOutput = 'center';

    /**
     * @var boolean
     */
    private $replaceOriginal = false;

    /**
     * Watermarker constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return
     */
    public function getTempPdf()
    {
        return $this->tempPdf;
    }

    /**
     * @param $tempPdf
     *
     * @return Watermarker
     */
    public function setTempPdf($tempPdf): Watermarker
    {
        $this->tempPdf = $tempPdf;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalPdf()
    {
        return $this->originalPdf;
    }

    /**
     * @param mixed $originalPdf
     *
     * @return Watermarker
     */
    public function setOriginalPdf($originalPdf)
    {
        $this->originalPdf = $originalPdf;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewPdf()
    {
        return $this->newPdf;
    }

    /**
     * @param mixed $newPdf
     *
     * @return Watermarker
     */
    public function setNewPdf($newPdf)
    {
        $this->newPdf = $newPdf;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWatermark()
    {
        return $this->watermark;
    }

    /**
     * @param mixed $watermark
     *
     * @return Watermarker
     */
    public function setWatermark($watermark)
    {
        $this->watermark = $watermark;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplaceOriginal()
    {
        return $this->replaceOriginal;
    }

    /**
     * @param mixed $replaceOriginal
     *
     * @return Watermarker
     */
    public function setReplaceOriginal($replaceOriginal)
    {
        $this->replaceOriginal = $replaceOriginal;

        return $this;
    }

    /**
     * @throws \Exception
     */
    private function validateAssets()
    {
        if ( ! file_exists($this->originalPdf)) {
            throw new \Exception("Inputted PDF file doesn't exist");
        } elseif ( ! file_exists($this->watermark->getWatermarkFile())) {
            throw new \Exception("Watermark doesn't exist.");
        }
    }

    /**
     * $position string -  'center','topright', 'topleft', 'bottomright', 'bottomleft'
     */
    // public function setWatermarkPosition($position)
    // {
    //     switch ($position) {
    //         case 'topright':
    //         case 'topleft':
    //         case 'bottomright':
    //         case 'bottomleft':
    //             $this->imagePositionOutput = $position;
    //             break;
    //         default:
    //             $this->imagePositionOutput = 'center';
    //     }
    // }

    private function watermarkWholePdf()
    {
        $pageCtr = $this->tempPdf->setSourceFile($this->originalPdf);
        for ($ctr = 1; $ctr <= $pageCtr; $ctr++) {
            $this->watermarkPage($ctr);
        }
    }

    private function watermarkPage($pageNumber)
    {
        $templateId = $this->tempPdf->importPage($pageNumber);
        $templateDimension = $this->tempPdf->getTemplateSize($templateId);
        if ($templateDimension['width'] > $templateDimension['height']) {
            $orientation = 'L';
        } else {
            $orientation = 'P';
        }

        $this->tempPdf->addPage($orientation, array($templateDimension['width'], $templateDimension['height']));
        $this->tempPdf->useTemplate($templateId);

        $wWidth = ($this->watermark->getWatermarkImageWidth() / 96) * 25.4; //in mm
        $wHeight = ($this->watermark->getWatermarkImageHeight() / 96) * 25.4; //in mm

        $watermarkPosition = $this->determineWatermarkPosition($wWidth,
            $wHeight,
            $templateDimension['width'],
            $templateDimension['height']);
        $this->tempPdf->Image($this->watermark->getWatermarkFile(), $watermarkPosition[0], $watermarkPosition[1], -96);
    }

    private function determineWatermarkPosition($wWidth, $wHeight, $tWidth, $tHeight)
    {

        switch ($this->imagePositionOutput) {
            case 'topleft':
                $x = 0;
                $y = 0;
                break;
            case 'topright':
                $x = $tWidth - $wWidth;
                $y = 0;
                break;
            case 'bottomright':
                $x = $tWidth - $wWidth;
                $y = $tHeight - $wHeight;
                break;
            case 'bottomleft':
                $x = 0;
                $y = $tHeight - $wHeight;
                break;
            default:
                $x = ($tWidth - $wWidth) / 2;
                $y = ($tHeight - $wHeight) / 2;
                break;
        }

        return array($x, $y);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function watermarkPdf()
    {
        $this->validateAssets();
        $this->watermarkWholePdf();
        if ($this->replaceOriginal && file_exists($this->originalPdf)) {
            try {
                @chmod($this->originalPdf, 0777);
                @unlink($this->originalPdf);
                $this->newPdf = $this->originalPdf;
            } catch (\Exception $e) {
                throw new \Exception('No permission to replace file');
            }
        }

        return $this->tempPdf->Output('F', $this->newPdf);
    }


}