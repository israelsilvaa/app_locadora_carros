<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        // $marca->nome = $request->nome;
        // $marca->nome = $imagem_urn;
        // $marca->save();

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
        // php artisan storage:link
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
        }else{
            $request->validate($marca->rules(), $marca->feedback());
        }

        // remove o arquivo antigo caso tenha sido atualizado
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        
        return response()->json($marca, 200);
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


        // remove o arquivo antigo
        Storage::disk('public')->delete($marca->imagem);
        
        $marca->delete();
        return response()->json(['msg'=>'a marca foi deletada com sucesso!'], 200);
    }
}
