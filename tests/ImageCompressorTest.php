<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\ImageCompressor;

class ImageCompressorTest extends TestCase
{
    protected $compressor;
    protected $sourcePath;
    protected $destinationPath;

    protected function setUp(): void
    {
        // مسیر فایل‌های تست
        $this->compressor = new ImageCompressor();
        $this->sourcePath = __DIR__ . '/sample_image.jpg';
        $this->destinationPath = __DIR__ . '/compressed_image.jpg';

        // ایجاد یک تصویر نمونه برای تست
        $image = imagecreatetruecolor(100, 100);
        imagejpeg($image, $this->sourcePath, 100);
        imagedestroy($image);
    }

    public function testImageCompression(): void
    {
        // فشرده‌سازی تصویر و بررسی موفقیت
        $result = $this->compressor->compress($this->sourcePath, $this->destinationPath, 75);
        $this->assertTrue($result, "Compression failed");

        // بررسی ایجاد فایل خروجی
        $this->assertFileExists($this->destinationPath, "Compressed file does not exist");

        // بررسی کاهش حجم فایل فشرده‌شده نسبت به فایل اصلی
        $originalSize = filesize($this->sourcePath);
        $compressedSize = filesize($this->destinationPath);
        $this->assertLessThan($originalSize, $compressedSize, "Compressed file is not smaller than the original file");
    }

    protected function tearDown(): void
    {
        // حذف فایل‌های تست پس از اتمام تست‌ها
        if (file_exists($this->sourcePath)) {
            unlink($this->sourcePath);
        }

        if (file_exists($this->destinationPath)) {
            unlink($this->destinationPath);
        }
    }
}
