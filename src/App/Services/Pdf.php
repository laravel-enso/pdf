<?php

namespace LaravelEnso\Pdf\App\Services;

use Barryvdh\Snappy\PdfWrapper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Pdf
{
    private PdfWrapper $pdf;
    private string $tempFile;

    public function __construct()
    {
        $this->pdf = $this->factory();
        $this->tempFile = $this->tempFile();
    }

    public function inline()
    {
        return $this->pdf->inline();
    }

    public function save($filePath): void
    {
        $this->pdf->save($filePath);
    }

    public function landscape(): self
    {
        $this->pdf->setOrientation('landscape');

        return $this;
    }

    public function setOption(string $option, $value): self
    {
        $this->pdf->setOption($option, $value);

        return $this;
    }

    public function loadView(string $view, array $attributes): self
    {
        $this->pdf->loadView($view, $attributes);

        return $this;
    }

    private function tempFile(): string
    {
        return 'temp/'.Str::random().'.pdf';
    }

    private function factory(): PdfWrapper
    {
        return App::make('snappy.pdf.wrapper')
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->setOption('margin-top', 5)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 10)
            ->setOption('footer-center', __('Page [page] from [toPage]'));
    }
}
