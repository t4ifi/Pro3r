<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\DetallePago;
use App\Models\Paciente;
use App\Models\Usuario;
use Carbon\Carbon;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos pacientes y usuarios existentes
        $pacientes = Paciente::take(3)->get();
        $usuarios = Usuario::take(2)->get();

        if ($pacientes->isEmpty() || $usuarios->isEmpty()) {
            $this->command->info('No hay pacientes o usuarios suficientes para crear pagos de prueba.');
            return;
        }

        foreach ($pacientes as $index => $paciente) {
            // Pago único completado
            $pagoUnico = Pago::create([
                'paciente_id' => $paciente->id,
                'concepto' => 'Limpieza dental',
                'monto_total' => 150.00,
                'modalidad_pago' => 'unico',
                'monto_pagado' => 150.00,
                'saldo_restante' => 0.00,
                'total_cuotas' => 1,
                'estado_pago' => 'completado',
                'fecha_creacion' => Carbon::now()->subDays($index * 5),
                'observaciones' => 'Pago completo al contado'
            ]);

            // Detalle del pago único
            DetallePago::create([
                'pago_id' => $pagoUnico->id,
                'monto_pagado' => 150.00,
                'fecha_pago' => $pagoUnico->fecha_creacion,
                'metodo_pago' => 'efectivo',
                'usuario_id' => $usuarios->first()->id,
                'observaciones' => 'Pago completo en efectivo'
            ]);

            // Pago en cuotas fijas (parcialmente pagado)
            if ($index < 2) {
                $pagoCuotas = Pago::create([
                    'paciente_id' => $paciente->id,
                    'concepto' => 'Tratamiento de ortodoncia',
                    'monto_total' => 1200.00,
                    'modalidad_pago' => 'cuotas_fijas',
                    'monto_pagado' => 400.00,
                    'saldo_restante' => 800.00,
                    'total_cuotas' => 6,
                    'estado_pago' => 'pendiente',
                    'fecha_creacion' => Carbon::now()->subDays($index * 3),
                    'observaciones' => 'Pago en 6 cuotas de $200 c/u'
                ]);

                // Primeros 2 pagos realizados
                for ($i = 1; $i <= 2; $i++) {
                    DetallePago::create([
                        'pago_id' => $pagoCuotas->id,
                        'monto_pagado' => 200.00,
                        'fecha_pago' => Carbon::now()->subDays(($index * 3) - $i),
                        'metodo_pago' => $i === 1 ? 'tarjeta' : 'transferencia',
                        'usuario_id' => $usuarios->random()->id,
                        'observaciones' => "Cuota #{$i} de 6"
                    ]);
                }
            }

            // Pago en cuotas variables (solo para el primer paciente)
            if ($index === 0) {
                $pagoVariable = Pago::create([
                    'paciente_id' => $paciente->id,
                    'concepto' => 'Implante dental',
                    'monto_total' => 2500.00,
                    'modalidad_pago' => 'cuotas_variables',
                    'monto_pagado' => 1000.00,
                    'saldo_restante' => 1500.00,
                    'total_cuotas' => 4,
                    'estado_pago' => 'pendiente',
                    'fecha_creacion' => Carbon::now()->subDays(10),
                    'observaciones' => 'Pago inicial de $1000, resto en cuotas variables'
                ]);

                // Pago inicial
                DetallePago::create([
                    'pago_id' => $pagoVariable->id,
                    'monto_pagado' => 1000.00,
                    'fecha_pago' => Carbon::now()->subDays(10),
                    'metodo_pago' => 'efectivo',
                    'usuario_id' => $usuarios->first()->id,
                    'observaciones' => 'Pago inicial del implante'
                ]);
            }
        }

        $this->command->info('Datos de prueba para pagos creados exitosamente.');
    }
}
