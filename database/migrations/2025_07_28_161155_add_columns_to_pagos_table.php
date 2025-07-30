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
        Schema::table('pagos', function (Blueprint $table) {
            // Columnas de montos y saldos
            $table->decimal('monto_pagado', 10, 2)->default(0)->after('monto_total');
            $table->decimal('saldo_restante', 10, 2)->default(0)->after('monto_pagado');
            
            // Modalidad de pago
            $table->enum('modalidad_pago', ['pago_unico', 'cuotas_fijas', 'cuotas_variables'])
                  ->default('pago_unico')->after('descripcion');
            
            // Información de cuotas
            $table->integer('total_cuotas')->nullable()->after('modalidad_pago');
            
            // Estado del pago
            $table->enum('estado_pago', ['pendiente', 'pagado_parcial', 'pagado_completo'])
                  ->default('pendiente')->after('total_cuotas');
            
            // Observaciones adicionales
            $table->text('observaciones')->nullable()->after('estado_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn([
                'monto_pagado',
                'saldo_restante', 
                'modalidad_pago',
                'total_cuotas',
                'estado_pago',
                'observaciones'
            ]);
        });
    }
};
