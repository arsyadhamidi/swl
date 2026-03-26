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
            $table->decimal('tot_harga');
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
