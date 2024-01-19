<?php

namespace App\Services;

interface Rates
{
    public function fetchRate(string $base_iso, string $rate_iso);
}