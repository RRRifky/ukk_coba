<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BarangController extends Controller
{
public function index(request $request) {
    $query = Barang::with('kategori');

    if ($request->search) {
        $query->where('id', 'like', '%' . $request->search . '%')
              ->orWhere('merk', 'like', '%' . $request->search . '%')
              ->orWhere('seri', 'like', '%' . $request->search . '%')
              ->orWhereHas('kategori', function ($q) use ($request) {
                  $q->where('deskripsi', 'like', '%' . $request->search . '%');
              });
    }

    $barang = $query->paginate(10);

    return view('vbarang.index', ['barang' => $barang]);
}
public function create() {
        $kat_id = Kategori::all();
        return view('vbarang.create',compact('kat_id'));
    }
    
    public function show(string $id)
    {   
        $rsetBarang = Barang::find($id);
        $deskKat = Barang::with('kategori')->where('id', $id)->first();
        return view('vbarang.show', compact('rsetBarang', 'deskKat'));
    }

public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'merk'     	=> 'required|string|max:50',
            'seri'     	=> 'nullable|string|max:50|unique:barang,seri',
            'spesifikasi'   => 'nullable|string',
            'stok'          => 'nullable|integer',
            'kategori_id'   => 'required|exists:kategori,id',
        ], [
            'seri.unique'   => 'Barang dengan seri ini sudah ada, silakan masukkan barang yang berbeda.',
        ]);
    
             if ($validator->fails()) {
                 return redirect()->route('barang.create')
                       ->withErrors($validator)
                        ->withInput();
        }
    
             Barang::create([
                 'merk'          => $request->merk,
            'seri'          => $request->seri,
            'spesifikasi'   => $request->spesifikasi,
            'stok'          => $request->stok,
            'kategori_id'   => $request->kategori_id,
        ]);
    
             return redirect()->route('barang.index')->with(['Success' => 'Data Barang Berhasil Disimpan!']);
    }
    
public function edit(string $id) {
        $idbar = Barang::find($id);
   $kategori_id = Kategori::all();
   return view('vbarang.edit', compact('idbar','kategori_id'));
}

public function update(Request $request, string $id) {
   $validator = Validator::make($request->all(), [
            'merk'          => 'required|string|max:50',
       'seri'          => 'nullable|string|max:50',
       'spesifikasi'   => 'nullable|string',
       'kategori_id'   => 'required|exists:kategori,id',
   ]);
        if ($validator->fails()) {
            return redirect()->route('barang.edit', $id)
                  ->withErrors($validator)
                   ->withInput();
   }
        $idbar = Barang::find($id);
        $idbar->update([
            'merk'          => $request->merk,
       'seri'          => $request->seri,
       'spesifikasi'   => $request->spesifikasi,
       'kategori_id'   => $request->kategori_id
   ]);
   return redirect()->route('barang.index')->with(['Success' => 'Data Barang Berhasil Diubah!']);
}

public function destroy(string $id) {
    $idkat = Barang::find($id);
    if ($idkat->stok > 0) {
        return redirect()->route('barang.index')->with(['error' => 'Barang dengan stok lebih dari 0 tidak dapat dihapus!']);
    }

	if (DB::table('barangmasuk','barangkeluar')->where('barang_id', $id)->exists()) {
     		return redirect()->route('barang.index')->with(['error' => 'Data barang gagal dihapus! Data barang masih digunakan oleh produk']);
	}
     	else {
     		$idkat = Barang::find($id);
		$idkat->delete();
		return redirect()->route('barang.index')->with(['Success' => 'Data barang berhasil dihapus!']);
	}
}


}
