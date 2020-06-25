<?php

namespace LaravelEnso\Pdf\Services;

use Barryvdh\Snappy\PdfWrapper;
use Illuminate\Support\Facades\App;

class Pdf
{
    private PdfWrapper $pdf;

    public function __construct()
    {
        $this->pdf = $this->factory();
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
