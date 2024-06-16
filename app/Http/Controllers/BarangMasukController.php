<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BarangMasukController extends Controller
{
public function index(Request $request) {
    $query = BarangMasuk::query()->with('barang');

    if ($request->has('search')) {
        $query->whereHas('barang', function ($q) use ($request) {
            $q->where('merk', 'like', '%' . $request->search . '%');
        })->orWhere('tgl_masuk', 'like', '%' . $request->search . '%');
    }

    $barangmasuk = $query->paginate(10);

    return view('vbarangmasuk.index', ['barangmasuk' => $barangmasuk]);
}
  
public function create() {
        $barang = DB::table('barang')->get();
             return view('vbarangmasuk.create', ['barang' => $barang]);
    }
    
    public function show(string $id)
    {   
        $rsetBarangMasuk = BarangMasuk::find($id);
        $deskBar = BarangMasuk::with('barang')->where('id', $id)->first();
        return view('vbarangmasuk.show', compact('rsetBarangMasuk', 'deskBar'));
    }

public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                 'qty_masuk' => 'required|integer',
            'barang_id' => 'required|exists:barang,id',
        ]);
             if ($validator->fails()) {
                 return redirect()->route('barangmasuk.create')
                       ->withErrors($validator)
                        ->withInput();
        }
             BarangMasuk::create([
                 'tgl_masuk'  => now()->toDateString(),
            'qty_masuk'  => $request->qty_masuk,
            'barang_id'  => $request->barang_id
        ]);
             return redirect()->route('barangmasuk.index')->with(['success' => 'Data Pemasukan Barang Berhasil Disimpan!']);
    }

    public function edit(string $id) {
        $idmasuk = BarangMasuk::find($id);
             $barang_id = Barang::all();
             return view('vbarangmasuk.edit', compact('idmasuk','barang_id'));
    }
    
    public function update(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
                 'qty_masuk'  => 'required',
            'barang_id'   => 'required'
        ]);
        $barang_id = BarangMasuk::find($id);
             $barang_id->update([
            'tgl_masuk'  => now()->toDateString(),
            'qty_masuk'  => $request->qty_masuk,
            'barang_id'   => $request->barang_id
        ]);
             return redirect()->route('barangmasuk.index')->with(['success' => 'Data Pengeluaran Barang Berhasil Diubah!']);
    }

public function destroy(string $id) {
    $datamasuk = BarangMasuk::findOrFail($id);  
    
    $referencedInBarangKeluar = BarangKeluar::where('barang_id', $datamasuk->barang_id)->exists();

    if ($referencedInBarangKeluar) {
    return redirect()->route('barangmasuk.index')->with(['error' => 'Data Tidak Bisa Dihapus Karena Masih Digunakan di Tabel Barang Keluar!']);
    }

    $datamasuk->delete();

    return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);

}

}
