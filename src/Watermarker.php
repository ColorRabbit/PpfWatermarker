<?php

namespace PdfWatermarker;

use setasign\Fpdi\Fpdi;

class Watermarker extends AbWatermarker
{
    use Watermark;

    public function __construct()
    {
        $this->setFpdi(new Fpdi());
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function watermarkPdf()
    {
        $this->validateAssets();
        $this->watermarkWholePdf();
        if ($this->isReplaceOriginal() && file_exists($this->getOriginalPdf())) {
            try {
                @chmod($this->getOriginalPdf(), 0777);
                @unlink($this->getOriginalPdf());
                $this->setNewPdf($this->getOriginalPdf());
            } catch (\Exception $e) {
                throw new \Exception('No permission to replace file');
            }
        }

        return $this->getFpdi()->Output('F', $this->getNewPdf());
    }

    /**
     * @throws \Exception
     */
    private function validateAssets()
    {
        if ( ! file_exists($this->getOriginalPdf())) {
            throw new \Exception("Inputted PDF file doesn't exist");
        } elseif ( ! file_exists($this->getWatermarkFile())) {
            throw new \Exception("Watermark doesn't exist.");
        }
    }

    /**
     * @throws \Exception
     */
    private function watermarkWholePdf()
    {
        $pageCtr = $this->getFpdi()->setSourceFile($this->getOriginalPdf());
        for ($ctr = 1; $ctr <= $pageCtr; $ctr++) {
            $this->watermarkPage($ctr);
        }
    }

    /**
     * @param $pageNumber
     *
     * @throws \Exception
     */
    private function watermarkPage($pageNumber)
    {
        $templateId = $this->getFpdi()->importPage($pageNumber);
        $templateDimension = $this->getFpdi()->getTemplateSize($templateId);
        if ($templateDimension['width'] > $templateDimension['height']) {
            $orientation = 'L';
        } else {
            $orientation = 'P';
        }

        $this->getFpdi()->addPage($orientation, array($templateDimension['width'], $templateDimension['height']));
        $this->getFpdi()->useTemplate($templateId);

        $wWidth = ($this->getWatermarkImageWidth() / 96) * 25.4; //in mm
        $wHeight = ($this->getWatermarkImageHeight() / 96) * 25.4; //in mm

        $watermarkPosition = $this->determineWatermarkPosition($wWidth, $wHeight, $templateDimension['width'], $templateDimension['height']);

        $this->getFpdi()->Image($this->getWatermarkFile(), $watermarkPosition[0], $watermarkPosition[1], -96);
    }

    /**
     * @param $wWidth
     * @param $wHeight
     * @param $tWidth
     * @param $tHeight
     *
     * @return array
     * @throws \Exception
     */
    private function determineWatermarkPosition($wWidth, $wHeight, $tWidth, $tHeight)
    {
        if ($this->getImagePosition() === self::TOP_LEFT) {
            return [0, 0];
        }

        if ($this->getImagePosition() === self::TOP_RIGHT) {
            return [$tWidth - $wWidth, 0];
        }

        if ($this->getImagePosition() === self::BOTTOM_RIGHT) {
            return [$tWidth - $wWidth, $tHeight - $wHeight];
        }

        if ($this->getImagePosition() === self::BOTTOM_LEFT) {
            return [0, $tHeight - $wHeight];
        }

        if ($this->getImagePosition() === self::CENTER) {
            return [($tWidth - $wWidth) / 2, ($tHeight - $wHeight) / 2];
        }

        throw new \Exception('Position not exists.');
    }


}