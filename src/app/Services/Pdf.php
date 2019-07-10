<?php

namespace LaravelEnso\Pdf\app\Services;

use Exception;
use mikehaertl\pdftk\Pdf as PdfTk;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Pdf\app\Exceptions\PdfException;

class Pdf
{
    private $pdf;
    private $watermark;
    private $tempFile;

    public function __construct()
    {
        $this->pdf = $this->factory();
        $this->tempFile = $this->tempFile();
    }

    private function factory()
    {
        return App::make('snappy.pdf.wrapper')
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-top', 5)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('footer-center', 'Pagina [page] din [toPage]');
    }

    public function watermark($watermark)
    {
        $this->watermark = $watermark;

        return $this;
    }

    public function landscape()
    {
        $this->pdf->setOrientation('landscape');

        return $this;
    }

    public function inline()
    {
        return $this->watermark
            ? $this->withWatermark()
            : $this->pdf->inline();
    }

    public function setOption(string $option, $value)
    {
        $this->pdf->setOption($option, $value);

        return $this;
    }

    public function loadView(string $view, array $attributes)
    {
        $this->pdf->loadView($view, $attributes);

        return $this;
    }

    private function withWatermark()
    {
        $this->validateWatermarkFile();

        try {
            $this->pdf->save($this->filePath(), true);

            return (new PdfTk($this->filePath()))
                ->background($this->watermark)
                ->send();
        } catch (Exception $e) {
            throw new PdfException(
                __('Unexpected exception encountered when writing temporary pdf to disk')
            );
        } finally {
            $this->cleanUp();
        }
    }

    private function validateWatermarkFile()
    {
        if (! File::isFile($this->watermark)) {
            throw new PdfException(
                __('Watermark file is missing from disk')
            );
        }
    }

    private function cleanUp()
    {
        Storage::delete($this->tempFile);
    }

    private function filePath()
    {
        return Storage::path($this->tempFile);
    }

    private function tempFile()
    {
        return 'temp/'.rand().'.pdf';
    }
}
