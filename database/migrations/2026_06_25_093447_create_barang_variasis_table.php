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
        Schema::create('barang_variasis', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('ukuran', 10);
            $table->string('warna', 50);
            $table->decimal('harga', 12,2);
            $table->integer('stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_variasis');
    }
};
