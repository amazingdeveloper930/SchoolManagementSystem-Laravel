<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Config;

class ConfigController extends Controller
{
    public function index()
    {
        return view('config.index');
    }


    public function getData(){

        $name = Config::where('type','name')->first();
        $firma = Config::where('type','firma')->first();


        return [
            'name' => $name,
            'firma' => $firma
        ];
    }


    public function create()
    {
        //
    }

    


    public function store(Request $request)
    {
        //
    }

    


    public function show($id)
    {
        //
    }

    


    public function edit($id)
    {
        //
    }

    


    public function update(Request $request)
    {   

        $name = Config::where('type','name')->get()->first();
        $firma = Config::where('type','firma')->get()->first();

        $firma_aux = Config::find($firma->id);
        $name_aux = Config::find($name->id);

        if($request->hasFile('firma')){
            // unlink(public_path('app/config/' . $firma->content));
            // $image = $request->file('firma');
            // $imageName = time().'.'.$image->getClientOriginalExtension();
            // $image->move(public_path('app/config/'), $imageName);
            // $firma->content = $imageName;
            // $firma->update();

            $image = $request->file('firma');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $path = $request->firma->storeAs('app/config', $imageName);
            $firma->content = $imageName;
            $firma->update();
        }

        $name->content = $request->get('name');
        $name->update();

        dd($request->get('name'));

        return ;
    }

    


    public function destroy($id)
    {
        //
    }
}
