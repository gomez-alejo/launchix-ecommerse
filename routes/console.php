<?php

use App\Models\Entrepreneur;
use App\Models\Product;
use App\Models\Servicio;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('owners:sync', function () {
    $this->info('Iniciando saneamiento de propietarios para productos y servicios...');

    $command = $this;
    $fixedProducts = 0;
    $skippedProducts = 0;

    Product::with('user')->orderBy('id')->chunk(100, function ($products) use (&$fixedProducts, &$skippedProducts, $command) {
        foreach ($products as $product) {
            $currentOwner = $product->entrepreneur_id ? Entrepreneur::find($product->entrepreneur_id) : null;
            if ($currentOwner) {
                continue;
            }

            $candidate = null;

            if ($product->user_id && ($matchById = Entrepreneur::find($product->user_id))) {
                $candidate = $matchById;
            } elseif ($product->relationLoaded('user') && $product->user && $product->user->email) {
                $candidate = Entrepreneur::where('email', $product->user->email)->first();
            }

            if (!$candidate) {
                $command->warn("Producto {$product->id} sin emprendedor asociado (" . ($product->name ?? 'sin nombre') . ")");
                $manual = $command->ask('Ingrese el ID del emprendedor para este producto (deje vacío para omitir)');
                if ($manual && Entrepreneur::whereKey($manual)->exists()) {
                    $candidate = Entrepreneur::find($manual);
                }
            }

            if (!$candidate) {
                $skippedProducts++;
                continue;
            }

            $product->entrepreneur_id = $candidate->id;
            $product->save();
            $fixedProducts++;
        }
    });

    $command->info("Productos actualizados: {$fixedProducts}");
    if ($skippedProducts > 0) {
        $command->warn("Productos pendientes sin asignar: {$skippedProducts}");
    }

    $fixedServices = 0;
    $skippedServices = 0;

    Servicio::orderBy('id')->chunk(100, function ($servicios) use (&$fixedServices, &$skippedServices, $command) {
        foreach ($servicios as $servicio) {
            $currentOwner = $servicio->user_id ? Entrepreneur::find($servicio->user_id) : null;
            if ($currentOwner) {
                continue;
            }

            $command->warn("Servicio {$servicio->id} sin emprendedor asociado (" . ($servicio->nombre_servicio ?? 'sin nombre') . ")");
            $manual = $command->ask('Ingrese el ID del emprendedor para este servicio (deje vacío para omitir)');
            if ($manual && Entrepreneur::whereKey($manual)->exists()) {
                $servicio->user_id = (int) $manual;
                $servicio->save();
                $fixedServices++;
            } else {
                $skippedServices++;
            }
        }
    });

    $command->info("Servicios actualizados: {$fixedServices}");
    if ($skippedServices > 0) {
        $command->warn("Servicios pendientes sin asignar: {$skippedServices}");
    }

    $this->info('Saneamiento finalizado.');
})->purpose('Sanea referencias de entrepreneur en productos y servicios');
