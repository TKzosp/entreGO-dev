<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        return redirect()->back()->with('success', 'Pedido recebido com sucesso.');
    }
}