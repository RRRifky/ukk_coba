@extends('layout.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangmasuk.update',$idmasuk->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">tgl_masuk</label>
                                <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror" name="tgl_masuk" value="{{ old('tgl_masuk',$idmasuk->tgl_masuk) }}" placeholder="Masukkan Tanggal">
                            
                                <!-- error message untuk nama -->
                                @error('tgl_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">qty_masuk</label>
                                <input type="text" class="form-control @error('qty_masuk') is-invalid @enderror" name="qty_masuk" value="{{ old('qty_masuk',$idmasuk->qty_masuk) }}" placeholder="Masukkan Nomor Induk Siswa">
                            
                                <!-- error message untuk qty_masuk -->
                                @error('qty_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">barang_id</label>
                                <!-- <input type="number" class="form-control @error('barang_id') is-invalid @enderror" name="barang_id" value="{{ old('barang_id',$idmasuk->barang_id) }}" placeholder="Masukkan Nomor Induk Siswa"> -->
                                <select name="barang_id" id="">
                                    <option value="{{ old('barang_id',$idmasuk->barang_id) }}">{{ old('barang_id',$idmasuk->barang_id) }}</option>
                                    @foreach($barang_id as $rowbarangID)
                                    <option value="{{ $rowbarangID->id }}">{{ $rowbarangID->id }} - {{ $rowbarangID->merk }}

                                    </option>
                                    @endforeach
                                </select>
                                <!-- error message untuk qty_masuk -->
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