<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStoreSettingRequest;
use App\Models\StoreSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class StoreSettingController extends Controller
{
    public function edit(): View
    {
        $configuracion = StoreSetting::current();

        return view('admin.configuracion.edit', compact('configuracion'));
    }

    public function update(UpdateStoreSettingRequest $request): RedirectResponse
    {
        StoreSetting::current()->update($request->validated());

        return redirect()->route('admin.configuracion.edit')->with('status', 'Configuración actualizada.');
    }
}
