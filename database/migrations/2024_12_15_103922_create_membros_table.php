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
        Schema::create('membros', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_fantasia')->nullable();
            $table->string('nome_crente', 150)->nullable();
            $table->string('telefone_fixo', 14)->nullable();
            $table->string('telefone_celular', 14)->nullable();
            $table->string('whatsapp', 14)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membros');
    }
};
