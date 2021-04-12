<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order = filter_var($request->input('order'), FILTER_VALIDATE_BOOLEAN);
        $articles = Article::orderBy('id', $order ? 'ASC' : 'DESC')->paginate(5)->toArray();
        return $articles;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'price' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'  
        ]);

        $article = new Article($request->except('image'));
        if($request->file()){
            $image = $request->file('image');
            $fileName = time().'_'.$image->getClientOriginalName();
            $filePath = $image->storeAs('images', $fileName, 'public');
            $article->image = $filePath;
        }

        $article->save();
        return response()->json('Registro creado con exito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'price' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'  
        ]);

        $article = Article::find($id);
        $article->update($request->except('image'));
        if($request->file()){
            $image = $request->file('image');
            $fileName = time().'_'.$image->getClientOriginalName();
            $filePath = $image->storeAs('images', $fileName, 'public');
            $article->image = $filePath;
        }

        $article->save();
        return response()->json('Registro actualizado con exito.');
    }

    public function changeStatus($id, Request $request)
    {
        $article = Article::find($id);
        $article->active = !$article->active;
        $article->save();
        return response()->json(["message"=>'Registro actualizado con exito.', "value"=> $article->active]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();
        
        return response()->json('Registro eliminado con exito.');

    }
}
