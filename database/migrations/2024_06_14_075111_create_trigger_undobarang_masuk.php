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
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER undo_barangmasuk
            AFTER DELETE ON barangmasuk
            FOR EACH ROW
            BEGIN
                DECLARE barang_stok INT;

                -- Ambil stok saat ini dari tabel barang
                SELECT stok INTO barang_stok FROM barang WHERE id = OLD.barang_id;

                -- Update stok di tabel barang
                UPDATE barang
                SET stok = barang_stok - OLD.qty_masuk
                WHERE id = OLD.barang_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS undo_barangmasuk');
    }
};
