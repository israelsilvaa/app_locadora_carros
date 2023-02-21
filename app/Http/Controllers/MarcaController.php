<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    protected $marca;
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $marcas = Marca::all();
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $marca = Marca::create($request->all());
        $request->validate($this->marca->rules(), $this->marca->feedback());
        // stateless
        
        // $request->nome;
        // dd($request->get('nome'));

        // $request->imagem;
        $imagem = $request->file('imagem');
        $imagem->store('imagens', 'public');
        dd('chegamos ate aqui');

        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro'=>'nada encontrado no banco de dados'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dados novos  
        // print_r($request->all());
        // dados antigos
        // print_r($marca);
        
        // $marca->update($request->all());
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json([
                'erro'=> 
                'o recurso que vc quer atualizar não existe no banco de dados'], 404);
        }
        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();
         
            // percorrendo todas as regras definidas no Model.
            foreach($marca->rules() as $input => $regra){
        
                // coletar apenas as regras aplicaveis aos parametros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
            $marca->update($request->all());
            return response()->json($marca, 200);
        }else{
            $request->validate($marca->rules(), $marca->feedback());
            $marca->update($request->all());
            return response()->json($marca, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $marca->delete();
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json([
                'erro'=> 
                'a exclusão não pode ser feita pois o recurso não existe'], 404);
        }
        $marca->delete();
        return response()->json(['msg'=>'a marca foi deletada com sucesso!'], 200);
    }
}
