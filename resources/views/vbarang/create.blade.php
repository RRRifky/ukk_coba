@extends('layout.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">                    
                            @csrf

                            <div class="form-group">
                                <label class="font-weight-bold">MERK</label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" name="merk" value="{{ old('merk') }}" placeholder="Masukkan Merk">
                            
                                <!-- error message untuk nama -->
                                @error('merk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">SERI</label>
                                <input type="text" class="form-control @error('seri') is-invalid @enderror" name="seri" value="{{ old('seri') }}" placeholder="Masukkan Seri">
                            
                                <!-- error message untuk nis -->
                                @error('seri')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">SPESIFIKASI</label>
                                <input type="text" class="form-control @error('spesifikasi') is-invalid @enderror" name="spesifikasi" value="{{ old('nis') }}" placeholder="Masukkan Spesifikasi">
                            
                                <!-- error message untuk nis -->
                                @error('spesifikasi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">STOK</label>
                                <!-- <input type="number" class="form-control @error('nis') is-invalid @enderror" name="stok" value="{{ old('nis') }}" placeholder="Masukkan Stok"> -->
                                (stok tidak dapat ditambahkan pada halaman ini,tambahkan stok pada barang masuk)
                                <!-- error message untuk nis -->
                                @error('stok')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">KATEGORI_ID</label>
                                <!-- <input type="number" class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id" value="{{ old('nis') }}" placeholder="Masukkan kategori_id"> -->
                                <!-- <select name="kategori" id="" class="form-control">
                                    @foreach($kat_id as $rowkategori)
                                    <option value="{{ $rowkategori->id }}">{{ $rowkategori->id }} - {{ $rowkategori->deskripsi }}</option>
                                    @endforeach
                                </select> -->
                                <select class="form-control " name="kategori_id" aria-label="Default select example">
                                        <option value="blank" selected>Pilih Kategori</option>
                                        @foreach($kat_id as $rowkategori)
                                            <option value="{{ $rowkategori->id }}">{{ $rowkategori->id }} - {{ $rowkategori->deskripsi }}</option>
                                        @endforeach
                                    </select>
                            
                                <!-- error message untuk nis -->
                                @error('kategori_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            

                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                            <a href="{{ route('barang.index') }}" class="btn btn-md btn-primary">Back</a>

                        </form> 
                    </div>
                </div>
                @if(session('Success'))
                    <div class="alert alert-success">
                        {{ session('Success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
 

            </div>
        </div>
    </div>
@endsection