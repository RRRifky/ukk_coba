<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BarangKeluarController extends Controller
{
public function index(Request $request) {
    $query = BarangKeluar::query()->with('barang');

    if ($request->has('search')) {
        $query->whereHas('barang', function ($q) use ($request) {
            $q->where('merk', 'like', '%' . $request->search . '%');
        })->orWhere('tgl_keluar', 'like', '%' . $request->search . '%');
    }

    $barangkeluar = $query->paginate(10);

    return view('vbarangkeluar.index', ['barangkeluar' => $barangkeluar]);
}

    public function create() {
        $barang = DB::table('barang')->get();
   return view('vbarangkeluar.create', ['barang' => $barang]);
}

    public function store(Request $request) {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'qty_keluar' => 'required|integer',
            'barang_id' => 'required|exists:barang,id',
        ]);
    
        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->route('barangkeluar.create')
                ->withErrors($validator)
                ->withInput();
        }
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangMasuk with a date later than tgl_keluar
        $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($existingBarangMasuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
        }
    
        // Memeriksa stok barang sebelum menyimpan
        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok < $request->qty_keluar) {
            return redirect()->route('barangkeluar.create')
                ->with('error', 'Stok barang tidak mencukupi!')
                ->withInput();
        }
    
        // Simpan data barang keluar jika stok mencukupi
        BarangKeluar::create([
            'tgl_keluar' => now()->toDateString(),
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id
        ]);
    
        return redirect()->route('barangkeluar.index')->with('success', 'Data Pengeluaran Barang Berhasil Disimpan!');
    }

public function edit(string $id) {
	$idkeluar = BarangKeluar::find($id);
     	$barang_id = Barang::all();
     	return view('vbarangkeluar.edit', compact('idkeluar','barang_id'));
}

public function update(Request $request, string $id) {
	$validator = Validator::make($request->all(), [
     		'qty_keluar'  => 'required',
		'barang_id'   => 'required'
	]);
    $tgl_keluar = $request->tgl_keluar;
    $barang_id = $request->barang_id;

    // Check if there's any BarangMasuk with a date later than tgl_keluar
    $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
        ->where('tgl_masuk', '>', $tgl_keluar)
        ->exists();

    if ($existingBarangMasuk) {
        return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
    }

    $barang = Barang::find($barang_id);

    if ($request->qty_keluar > $barang->stok) {
        return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
    }
	$barang_id = Barangkeluar::find($id);
     	$barang_id->update([
		'tgl_keluar'  => now()->toDateString(),
		'qty_keluar'  => $request->qty_keluar,
		'barang_id'   => $request->barang_id
	]);
     	return redirect()->route('barangkeluar.index')->with(['success' => 'Data barangkeluar Berhasil Diubah!']);
}

public function destroy(string $id) {
    $barang_id = BarangKeluar::find($id);
    $barang_id->delete();
return redirect()->route('barangkeluar.index')->with(['success' => 'Data Pemasukan Barang Berhasil Dihapus!']);
}
}
