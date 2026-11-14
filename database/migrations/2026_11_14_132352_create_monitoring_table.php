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
    Schema::create('monitorings', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->foreignId('kebun_id')->constrained('kebuns')->onDelete('cascade');
        $table->string('jenis_gangguan');
        $table->string('nama_gangguan')->nullable();
        $table->text('keterangan');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
