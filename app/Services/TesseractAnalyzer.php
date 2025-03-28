<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\OcrAnalyzerContract;
use Exception;
use thiagoalessio\TesseractOCR\TesseractOCR;

final readonly class TesseractAnalyzer implements OcrAnalyzerContract
{
    private TesseractOCR $ocr;

    public function __construct()
    {
        $this->ocr = new TesseractOCR;
    }

    public function analyzeDocument(string $filepath): string
    {
        try {
            $image = $this->ocr->image($filepath);

            return $this->ocr->run();
        } catch (Exception) {
            return '';
        }
    }
}
