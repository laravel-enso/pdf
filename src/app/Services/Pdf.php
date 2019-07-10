<?php

namespace LaravelEnso\Pdf\app\Services;

use Exception;
use mikehaertl\pdftk\Pdf as PdfTk;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Pdf
{
    private $pdf;
    private $watermark;

    public function __construct()
    {
        $this->pdf = $this->factory();
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

        $tempFile = 'temp/'.rand().'.pdf';
        $file = Storage::path($tempFile);

        try {
            $this->pdf->save($file, true);

            $pdfTk = new PdfTk($file);

            return $pdfTk->background($this->watermark)
                ->send();
        } catch (Exception $e) {
        } finally {
            Storage::delete($tempFile);
        }
    }

    private function validateWatermarkFile()
    {
        if (! File::isFile($this->watermark)) {
            throw new WatermarkException(
                __('Watermark file is missing from disk')
            );
        }
    }
}
