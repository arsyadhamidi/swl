<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('users_id');
            $table->timestamp('tgl_pesanan');
            $table->decimal('tot_harga', 12, 2);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->text('alamat_pengiriman');
            $table->string('telp', 20);
            $table->string('bukti_pembayaran');
            $table->enum('status', ['Pending', 'Diproses', 'Selesai', 'Dibatalkan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
