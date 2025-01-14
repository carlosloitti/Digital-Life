<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedidoCollection;
use Carbon\Carbon;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PedidoCollection(Pedido::with('user')->with('productos')->where('estado' , 0)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pedido = new Pedido;
        $pedido->user_id = Auth::user()->id;
        $pedido->total = $request->total;
        $pedido->save();

        //id del pedido
        $id = $pedido->id;
        //obtener los productos
        $productos = $request->productos;
        // formatear un arreglo
        $pedido_producto = [];

        foreach($productos as $producto) {
            $pedido_producto[] = [
                'pedido_id' => $id,
                'producto_id' => $producto[ 'id'],
                'cantidad' =>  $producto[ 'cantidad'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // almacenar en la db
        PedidoProducto::insert($pedido_producto);

        return [
            'message' => 'Pedido realizado correctamente' 
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        $pedido->estado = 1;
        $pedido->save();

        return [
            'pedido' => $pedido
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
