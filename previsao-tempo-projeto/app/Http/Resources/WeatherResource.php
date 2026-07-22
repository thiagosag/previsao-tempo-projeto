<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'city'        => $this['city'] ?? 'Desconhecida',
            'temperature' => $this['temperature'] ?? 0.0,
            'condition'   => $this['condition'] ?? 'Sem dados',
            'cached'      => $this['cached'] ?? false,
            'formatted'   => sprintf(
                "%s: %s°C - %s",
                $this['city'] ?? '',
                $this['temperature'] ?? 0,
                $this['condition'] ?? ''
            ),
        ];
    }
}
