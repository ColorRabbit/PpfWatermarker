<?php

namespace PdfWatermarker;

/**
 * Trait Watermark
 * @package PdfWatermarker
 */
trait Watermark
{
    /**
     * @var $watermarkFile
     */
    private $watermarkFile;

    /**
     * @var $watermarkImageWidth
     */
    private $watermarkImageWidth;

    /**
     * @var $watermarkImageHeight
     */
    private $watermarkImageHeight;

    /**
     * @var $watermarkImageMime
     */
    private $watermarkImageMime;

    /**
     * @return string
     */
    public function getWatermarkFile(): string
    {
        return $this->watermarkFile;
    }

    /**
     * @param string $watermarkFile
     *
     * @return $this
     * @throws \Exception
     */
    public function setWatermarkFile(string $watermarkFile)
    {
        $this->watermarkFile = $this->prepareWatermarkImage($watermarkFile);
        $imageSize = getimagesize($this->watermarkFile);

        $this->watermarkImageWidth = $imageSize[0];
        $this->watermarkImageHeight = $imageSize[1];
        $this->watermarkImageMime = $imageSize['mime'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWatermarkImageWidth()
    {
        return $this->watermarkImageWidth;
    }

    /**
     * @return mixed
     */
    public function getWatermarkImageHeight()
    {
        return $this->watermarkImageHeight;
    }

    /**
     * @return mixed
     */
    public function getWatermarkImageMime()
    {
        return $this->watermarkImageMime;
    }

    /**
     * @param $file
     *
     * @return string
     * @throws \Exception
     */
    private function prepareWatermarkImage($file)
    {

        if (!file_exists($file)) {
            throw new \Exception("Watermark doesn't exist.");
        }
        $imageType = exif_imagetype($file);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file);
                $path = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                imageinterlace($image, false);
                imagejpeg($image, $path);
                imagedestroy($image);
                break;

            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file);
                $path = sys_get_temp_dir() . '/' . uniqid() . '.png';
                imageinterlace($image, false);
                imagesavealpha($image, true);
                imagepng($image, $path);
                imagedestroy($image);
                break;

            default:
                throw new \Exception("Unsupported image type");
                break;
        };

        return $path;

    }

}