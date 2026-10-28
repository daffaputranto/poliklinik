<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    private function rules(): array
    {
        return [
            'nama_obat' => 'required|string|max:255',
            'kemasan'   => 'required|string|max:35',
            'stok'      => 'required|integer|min:0',
            'harga'     => 'required|integer|min:0',
        ];
    }

    private function messages(): array
    {
        return [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_obat.max'      => 'Nama obat maksimal 255 karakter.',
            'kemasan.required'   => 'Kemasan wajib diisi.',
            'kemasan.max'        => 'Kemasan maksimal 35 karakter.',
            'stok.required'      => 'Stok wajib diisi.',
            'stok.integer'       => 'Stok harus berupa angka bulat.',
            'stok.min'           => 'Stok tidak boleh negatif.',
            'harga.required'     => 'Harga wajib diisi.',
            'harga.integer'      => 'Harga harus berupa angka bulat.',
            'harga.min'          => 'Harga tidak boleh negatif.',
        ];
    }

    public function index()
    {
        $obats = Obat::latest()->get();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        Obat::create($validated);

        return redirect()->route('obat.index')
            ->with('message', 'Data obat berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        Obat::findOrFail($id)->update($validated);

        return redirect()->route('obat.index')
            ->with('message', 'Data obat berhasil diperbarui')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        Obat::findOrFail($id)->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data obat berhasil dihapus')
            ->with('type', 'success');
    }
}