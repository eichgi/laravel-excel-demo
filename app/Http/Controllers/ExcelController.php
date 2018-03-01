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
        /** Creamos un archivo llamado fromBlade.xlsx */
        Excel::create('fromBlade', function ($excel) {

            /** La hoja se llamará Usuarios */
            $excel->sheet('Usuarios', function ($sheet) {
                /** El método loadView nos carga la vista blade a utilizar */
                $sheet->loadView('usuarios');
            });

            /** Agregará una segunda hoja y se llamará Productos */
            $excel->sheet('Productos', function ($sheet) {
                $sheet->loadView('productos');
            });
        })->download('csv');
    }

    public function exportExcel()
    {
        /** Fuente de Datos (Array) */
        /*$data = [
            ['Nombre', 'Hiram Guerrero'],
            ['Edad', '27'],
            ['Profesión', 'Desarrollador de Software'],
        ];*/

        /** Fuente de Datos Eloquent */
        $data = User::all();

        /** Creamos nuestro archivo Excel */
        Excel::create('usuarios', function ($excel) use ($data) {

            /** Definimos los metadatos
             * $excel->setTitle('Usuarios');
             * $excel->setCreator('Eichgi');
             * $excel->setDescription('Creando mi primera hoja en excel con Laravel!');*/

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
        })->download('csv');
    }

    public function importExcel()
    {
        /** El método load permite cargar el archivo definido como primer parámetro */
        Excel::load('productos.csv', function ($reader) {
            /**
             * $reader->get() nos permite obtener todas las filas de nuestro archivo
             */
            foreach ($reader->get() as $key => $row) {
                $producto = [
                    'articulo' => $row['articulo'],
                    'cantidad' => $row['cantidad'],
                    'precio_unitario' => $row['precio_unitario'],
                    'fecha_registro' => $row['fecha_registro'],
                    'status' => $row['status'],
                ];

                /** Una vez obtenido los datos de la fila procedemos a registrarlos */
                if (!empty($producto)) {
                    DB::table('productos')->insert($producto);
                }
            }

            echo 'Los productos han sido importados exitosamente';
        });
    }

    public function importFromFile(Request $request)
    {
        /** Cargando el excel mediante un archivo recibido vía POST con name=productos */
        Excel::load($request->productos->getRealPath(), function ($reader) {
            /**
             * $reader->get() nos permite obtener todas las filas de nuestro archivo
             */
            foreach ($reader->get() as $key => $row) {
                $producto = [
                    'articulo' => $row['articulo'],
                    'cantidad' => $row['cantidad'],
                    'precio_unitario' => $row['precio_unitario'],
                    'fecha_registro' => $row['fecha_registro'],
                    'status' => $row['status'],
                ];

                /** Una vez obtenido los datos de la fila procedemos a registrarlos */
                if (!empty($producto)) {
                    DB::table('productos')->insert($producto);
                }
            }

            echo 'Los productos han sido importados exitosamente';
        });
    }
}
