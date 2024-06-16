<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER kurangi_setelah_keluar
            AFTER INSERT ON barangkeluar
            FOR EACH ROW
            BEGIN
                DECLARE barang_stok INT;

                -- Ambil stok saat ini dari tabel barang
                SELECT stok INTO barang_stok FROM barang WHERE id = NEW.barang_id;

                -- Cek apakah stok mencukupi
                    -- Update stok di tabel barang
                    UPDATE barang
                    SET stok = barang_stok - NEW.qty_keluar
                    WHERE id = NEW.barang_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_setelah_keluar');
    }
};