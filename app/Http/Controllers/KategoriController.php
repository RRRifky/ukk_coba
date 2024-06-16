<?php

namespace App\Http\Controllers;
use App\Models\Kategori;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class KategoriController extends Controller
{
public function index(request $request){
    // if ($request->search){
    //     $kategori = DB::table('kategori')->select('id','deskripsi','kategori',DB::raw('ketKategorik(kategori) as kat'))
    //                                          ->where('id','like','%'.$request->search.'%')
    //                                          ->orWhere('deskripsi','like','%'.$request->search.'%')
    //                                          ->orWhere('kategori','like','%'.$request->search.'%')
    //                                          ->orWhereRaw('ketKategorik(kategori)','like','%',$request->search.'%')
    //                                          ->paginate(10);}
    //                                         else {
    //                                             $kategori = DB::table('kategori')->select('id','deskripsi','kategori',DB::raw('ketKategorik(kategori) as kat'))->paginate(10);
    //                                         }
        
    //     return view('vkategori.index', ['kategori' => $kategori]);
    $search = $request->search;

    $kategori = DB::table('kategori')
        ->select('id', 'deskripsi', 'kategori', DB::raw('ketKategorik(kategori) as ketkategorik
        '))
        ->when($search, function ($query, $search) {
            return $query->where('id', 'like', '%' . $search . '%')
            ->orWhere('deskripsi', 'like', '%' . $search . '%')
            ->orWhere('kategori', 'like', '%' . $search . '%')
            ->orWhereRaw('ketKategorik(kategori) LIKE ?', ['%' . $search . '%']);
    })
        ->paginate(10);

    return view('vkategori.index', ['kategori' => $kategori]);
}

public function create() {
        return view('vkategori.create');
    }
    
    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);
        

        return view('vkategori.show', compact('rsetKategori'));
    }

public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                 'deskripsi'  => 'required|unique:kategori',
            'kategori'   => 'required'
        ]);
    
        if ($validator->fails()) {
                 return redirect()->route('kategori.create')
                       ->withErrors($validator)
                        ->withInput();
        }
    
             Kategori::create([
                 'deskripsi'  => $request->deskripsi,
            'kategori'   => $request->kategori
        ]);
    
             return redirect()->route('kategori.index')->with(['Success' => 'Data kategori berhasil disimpan!']);
    }
    
public function edit(string $id) {
        $idkat = Kategori::find($id);
        return view('vkategori.edit', compact('idkat'));
    }
    
public function update(Request $request, string $id) {
        $request->validate([
                 'deskripsi'  => 'required',
            'kategori'   => 'required|in:M,A,BHP,BTHP'
        ]);
             $idkat = Kategori::find($id);
             $idkat->update([
                 'deskripsi'  => $request->deskripsi,
            'kategori'   => $request->kategori
        ]);
        return redirect()->route('kategori.index')->with(['Success' => 'Data kategori berhasil diubah!']);
    }
    
    public function destroy(string $id) {
        if (DB::table('barang')->where('kategori_id', $id)->exists()) {
                 return redirect()->route('kategori.index')->with(['error' => 'Data kategori gagal dihapus! Data kategori masih digunakan oleh produk']);
        }
             else {
                 $idkat = Kategori::find($id);
            $idkat->delete();
            return redirect()->route('kategori.index')->with(['Success' => 'Data kategori berhasil dihapus!']);
        }
    }
    
}
