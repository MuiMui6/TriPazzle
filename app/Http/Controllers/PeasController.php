<?php

namespace App\Http\Controllers;

use App\Peas;
use Illuminate\Http\Request;

class PeasController extends Controller
{
//======================================================================================================================
//Search
//======================================================================================================================
    public function adminview()
    {
        $message = null;
        $peases = Peas::join('users', 'users.id', '=', 'peases.createrid')
            ->select([
                'peases.id as id',
                'peases.cnt as cnt',
                'peases.created_at as created_at',
                'peases.updated_at as updated_at',
                'users.name as creatername'
            ])
            ->orderBy('peases.id', '1')
            ->paginate(9);


        return view('/admin/All_Peas', compact('peases','message'));

    }


//======================================================================================================================
//Search
//======================================================================================================================
    public function search(Request $request)
    {

        $message = null;

        if ($request->keyword <> null) {

            $vkeyword = $request->validate(['keyword' => 'regex:/^[0-9a-zA-Z０-９ぁ-んァ-ヶー一-龠]+$/']);
            $vkeyword = implode($vkeyword);

            $peases = Peas::join('users', 'users.id', '=', 'peases.createrid')
                ->select([
                    'peases.id as id',
                    'peases.cnt as cnt',
                    'peases.created_at as created_at',
                    'peases.updated_at as updated_at',
                    'users.name as creatername'
                ])
                ->where($request->clumn, 'like', '%' . $vkeyword . '%')
                ->orderBy('peases.id', '1')
                ->paginate(9);
            return view('/admin/All_Peas', compact('peases','message'));
        }
        return redirect('/admin/All_Peas');
    }



//======================================================================================================================
//Create
//======================================================================================================================
    public function create(Request $request)
    {

        //peas
        $vpeas = $request->validate(['peas' => 'regex:/^[0-9]+$/']);
        $vpeas = implode($vpeas);


        $c_peas = Peas::where('peases.cnt', $vpeas)
            ->count('id');

        if ($c_peas == '0') {

            Peas::insert([
                'cnt' => $vpeas,
                'createrid' => $request->userid,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $message = '作成しました';
        }else{
            $message = '既に作成されております';
        }

        $peases = Peas::join('users', 'users.id', '=', 'peases.createrid')
            ->select([
                'peases.id as id',
                'peases.cnt as cnt',
                'peases.created_at as created_at',
                'peases.updated_at as updated_at',
                'users.name as creatername'
            ])
            ->orderBy('peases.id', '1')
            ->paginate(9);


        return view('/admin/All_Peas', compact('peases','message'));
    }


//======================================================================================================================
//
//======================================================================================================================
    //public function (){
    //
    //}
}
