<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{

    public function bladeToExcel()
    {
        Excel::create('fromBlade', function ($excel) {
            $excel->sheet('Usuarios', function ($sheet) {
                $sheet->loadView('usuarios');
            });

            $excel->sheet('Productos', function ($sheet) {
                $sheet->loadView('productos');
            });
        })->download('xlsx');
    }

    public function exportExcel()
    {
        /** Fuente de Datos (Array) */
        $data = [
            ['Nombre', 'Hiram Guerrero'],
            ['Edad', '27'],
            ['Profesión', 'Desarrollador de Software'],
        ];

        /** Fuente de Datos Eloquent */
        $data = User::all();

        /** Creamos nuestro archivo Excel */
        Excel::create('test', function ($excel) use ($data) {

            /** Definimos los metadatos */
            $excel->setTitle('Usuarios');
            $excel->setCreator('Eichgi');
            $excel->setDescription('Creando mi primera hoja en excel con Laravel!');

            /** Creamos una hoja */
            $excel->sheet('Hoja Uno', function ($sheet) use ($data) {
                /**
                 * Insertamos los datos en la hoja con el método with/fromArray
                 * Parametros: (
                 * Datos,
                 * Valores del encabezado de la columna,
                 * Celda de Inicio,
                 * Comparación estricta de los valores del encabezado
                 * Impresión de los encabezados
                 * )*/
                $sheet->with($data, null, 'A1', false, false);
            });

            /** Descargamos nuestro archivo pasandole la extensión deseada (xls, xlsx) */
        })->download('xlsx');
    }

    public function importExcel(Request $request)
    {
        /**
         * Si deseas cargar el archivo desde el root del proyecto solo debes escribir el nombre como se muestra en este ejemplo
         * En cambio si deseas recibir el proyecto a través de un formulario entonces debes hacerlo mediante el request:
         * $request->file('productos')->getRealPath()
         */
        Excel::load('productos.xlsx', function ($reader) {
            foreach ($reader->get() as $key => $row) {
                $producto = [
                    'articulo' => $row['articulo'],
                    'cantidad' => $row['cantidad'],
                    'precio_unitario' => $row['precio_unitario'],
                    'fecha_registro' => $row['fecha_registro'],
                    'status' => $row['status'],
                ];

                if (!empty($producto)) {
                    DB::table('productos')->insert($producto);
                }
            }

            echo 'Los productos han sido importados exitosamente';
        });
    }
}
