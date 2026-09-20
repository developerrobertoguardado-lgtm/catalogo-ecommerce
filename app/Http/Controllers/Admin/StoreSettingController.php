<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStoreSettingRequest;
use App\Models\StoreSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    public function edit(): View
    {
        $configuracion = StoreSetting::current();

        return view('admin.configuracion.edit', compact('configuracion'));
    }

    public function update(UpdateStoreSettingRequest $request): RedirectResponse
    {
        $configuracion = StoreSetting::current();
        $datos = $request->safe()->except(['logo', 'eliminar_logo']);

        if ($request->boolean('eliminar_logo') || $request->hasFile('logo')) {
            if ($configuracion->logo_path) {
                Storage::disk('public')->delete($configuracion->logo_path);
            }

            $datos['logo_path'] = $request->hasFile('logo')
                ? $request->file('logo')->store('branding', 'public')
                : null;
        }

        $configuracion->update($datos);
        StoreSetting::flushCache();

        return redirect()->route('admin.configuracion.edit')->with('status', 'Configuración actualizada.');
    }
}
