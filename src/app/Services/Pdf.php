<?php

namespace LaravelEnso\Pdf\app\Services;

use Illuminate\Support\Facades\App;

class Pdf
{
    private $pdf;
    private $tempFile;

    public function __construct()
    {
        $this->pdf = $this->factory();
        $this->tempFile = $this->tempFile();
    }

    public function inline()
    {
        return $this->pdf->inline();
    }

    public function save($filePath)
    {
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
