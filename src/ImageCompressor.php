<?php

namespace Src;

require "vendor/autoload.php";

class ImageCompressor
{
    /**
     * Compress an image to the given quality and save it to the specified path.
     *
     * @param string $sourcePath Path to the source image.
     * @param string $destinationPath Path to save the compressed image.
     * @param int $quality Compression quality (0 to 100).
     * @return bool True if successful, false otherwise.
     */
    public function compress(string $sourcePath, string $destinationPath, int $quality = 75): bool
    {
        // Check if the file exists
        if (!file_exists($sourcePath)) {
            echo "Error: Source file does not exist.";
            return false;
        }

        // Get the image information
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            echo "Error: Unsupported image format.";
            return false;
        }

        // Extract image type and create image resource
        $mime = $imageInfo['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                // Adjust quality for PNG (0 - 9)
                $quality = (int)($quality / 10);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                echo "Error: Unsupported image type.";
                return false;
        }

        // Save the compressed image to the destination path
        $result = false;
        switch ($mime) {
            case 'image/jpeg':
                $result = imagejpeg($image, $destinationPath, $quality);
                break;
            case 'image/png':
                $result = imagepng($image, $destinationPath, $quality);
                break;
            case 'image/gif':
                $result = imagegif($image, $destinationPath);
                break;
        }

        // Free up memory
        imagedestroy($image);

        return $result;
    }
}
