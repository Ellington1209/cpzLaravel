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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->nullable(); // Nome do usuário (se aplicável)
            $table->string('phone_number')->nullable();// Número do destinatário
            $table->text('message')->nullable();// Mensagem enviada
            $table->string('status')->nullable();// Status (Enviado/Erro)
            $table->text('details')->nullable(); // Detalhes adicionais do envio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_logs');
    }
};
