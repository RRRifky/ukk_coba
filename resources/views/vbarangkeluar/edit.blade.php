@extends('layout.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangkeluar.update', $idkeluar->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">tgl_keluar</label>
                                <input type="date" class="form-control @error('tgl_keluar') is-invalid @enderror" name="tgl_keluar" value="{{ old('tgl_keluar', $idkeluar->tgl_keluar) }}" placeholder="Masukkan Tanggal">
                            
                                <!-- error message untuk nama -->
                                @error('tgl_keluar')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">qty_keluar</label>
                                <input type="text" class="form-control @error('qty_keluar') is-invalid @enderror" name="qty_keluar" value="{{ old('qty_keluar', $idkeluar->qty_keluar) }}" placeholder="Masukkan QTY">
                            
                                <!-- error message untuk qty_keluar -->
                                @error('qty_keluar')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">barang_id</label>
                                <select name="barang_id" class="form-control @error('barang_id') is-invalid @enderror">
                                    <option value="{{ old('barang_id', $idkeluar->barang_id) }}">{{ old('barang_id', $idkeluar->barang_id) }}</option>
                                    @foreach($barang_id as $rowbarangID)
                                        <option value="{{ $rowbarangID->id }}">{{ $rowbarangID->id }} - {{ $rowbarangID->merk }}</option>
                                    @endforeach
                                </select>
                                <!-- error message untuk barang_id -->
                                @error('barang_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                            <a href="{{ route('barangmasuk.index') }}" class="btn btn-md btn-primary">Back</a>


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
