<?php

namespace PdfWatermarker;

abstract class AbWatermarker
{
    const CENTER = 'center';

    const TOP_LEFT = 'top-left';

    const TOP_RIGHT = 'top-right';

    const BOTTOM_LEFT = 'bottom-left';

    const BOTTOM_RIGHT = 'bottom-right';

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
    private $fpdi;

    /**
     * @var
     */
    private $watermark;

    /**
     * @var string
     */
    private $imagePosition = self::CENTER;

    /**
     * @var boolean
     */
    private $replaceOriginal = false;

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
     * @return AbWatermarker
     */
    public function setNewPdf($newPdf)
    {
        $this->newPdf = $newPdf;

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
     * @return $this
     */
    public function setOriginalPdf($originalPdf)
    {
        $this->originalPdf = $originalPdf;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFpdi()
    {
        return $this->fpdi;
    }

    /**
     * @param mixed $fpdi
     *
     * @return $this
     */
    public function setFpdi($fpdi)
    {
        $this->fpdi = $fpdi;

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
     * @return $this
     */
    public function setWatermark($watermark)
    {
        $this->watermark = $watermark;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePosition(): string
    {
        return $this->imagePosition;
    }

    /**
     * @param string $imagePosition
     *
     * @return $this
     */
    public function setImagePosition(string $imagePosition)
    {
        $this->imagePosition = $imagePosition;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReplaceOriginal(): bool
    {
        return $this->replaceOriginal;
    }

    /**
     * @param bool $replaceOriginal
     *
     * @return $this
     */
    public function setReplaceOriginal(bool $replaceOriginal)
    {
        $this->replaceOriginal = $replaceOriginal;

        return $this;
    }


}