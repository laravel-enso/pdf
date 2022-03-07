<?php

namespace LaravelEnso\Pdf\Contracts;

use Illuminate\Http\Response;

interface GeneratesPdf
{
    public function inline(): Response;

    public function output(): string;
}
