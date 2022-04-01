<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Venta;
use App\Models\Comercio;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class VentaController extends Controller
{

    public function create(Request $res)
    {


        $validated = $res->validate([
            'monto' => 'required',
            'comercio_id' => 'required',
            'dispositivo_id' => 'required',
            'mac_terminal' => 'required'
        ]);

        $com = Comercio::find($res->comercio_id);
        if (!$com) {
            return response()->json(
                [
                    'msg' => 'error',
                    "info" => "id de comercio no registrado"

                ]

            );
        }

        $venta = new Venta;

        $codigo = Str::random(20);

        $venta->comercio_id = $com->id;
        $venta->monto = $res->monto;
        $venta->dispositivo_id = $res->dispositivo_id;
        $venta->cod = Crypt::encryptString($codigo);
        $venta->terminal_mac = $res->mac_terminal;
        $venta->terminal_id = $res->dispositivo_id;
        $venta->delete = 0;
        $venta->save();

        $com->puntos =  $com->puntos + 10;
        $com->save();

        return response()->json(
            [
                'msg' => 'ok',
                'data' => [
                    "id" => $venta->id,
                    "id_dispositrivo" => $res->dispositivo_id,
                    "codigo_seguridad" => $codigo
                ]
            ]
        );
    }

    public function delete(Request $res)
    {

        $validated = $res->validate([
            'id' => 'required',
            'codigo_seguridad' => 'required',
        ]);

        $venta = Venta::find($res->id); // !Hash::check

        if (!$venta or $venta->delete == 1) {
            return response()->json(

                [
                    'msg' => 'error',
                    'info' => 'venta no localizada'

                ]
            );
        }

        try {

            $cod_decrypted = Crypt::decryptString($venta->cod);
        } catch (DecryptException  $e) {
            return response()->json(

                [
                    'msg' => 'error',
                    'info' => 'error al validar datos' . $e

                ]
            );
        }
        if ($res->codigo_seguridad === $cod_decrypted) {

            $venta->delete = 1;
            $venta->save();

            $com = Comercio::find($venta->comercio_id);
            $com->puntos =   $com->puntos - 10;
            $com->save();

            return response()->json(
                [
                    'msg' => 'ok',
                    'data' => [
                        'id' => $venta->id,
                        'cod_seguridad' => $cod_decrypted
                    ]

                ]
            );
        } else {

            return response()->json(
                [
                    'msg' => 'error',
                    'info' => 'error al validar datos',

                ]
            );
        }
    }
}
