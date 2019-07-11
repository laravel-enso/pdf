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
    private $pdfTk;
    private $tempFile;

    public function __construct()
    {
        $this->pdf = $this->factory();
        $this->tempFile = $this->tempFile();
    }

    public function inline()
    {
        if (isset($this->pdfTk)) {
            $response = $this->pdfTk->send();
            $this->cleanUp();

            return $response;
        }

        return $this->pdf->inline();
    }

    public function save($filePath)
    {
        if (isset($this->pdfTk)) {
            $this->pdfTk->saveAs($filePath);
            
            return;
        }

        $this->pdf->save($filePath);
    }

    public function landscape()
    {
        $this->pdf->setOrientation('landscape');

        return $this;
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

    public function withWatermark($watermark)
    {
        $this->validateWatermark($watermark);
        $this->pdf->save($this->tempFilePath(), true);

        try {           
            $this->pdfTk = (new PdfTk($this->tempFilePath()))
                ->background($watermark);
        } catch (Exception $e) {
            \Log::debug($e);
            $this->cleanUp();
            throw new PdfException(
                __('Unexpected exception encountered when writing temporary pdf to disk')
            );
        }
        
        return $this;
    }

    private function validateWatermark($watermark)
    {
        if (! File::isFile($watermark)) {
            throw new PdfException(
                __('Watermark file is missing from disk')
            );
        }
    }

    private function cleanUp()
    {
        Storage::delete($this->tempFile);
    }

    private function tempFilePath()
    {
        return Storage::path($this->tempFile);
    }

    private function tempFile()
    {
        return 'temp/'.rand().'.pdf';
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
}
