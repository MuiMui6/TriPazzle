<?php

namespace App\Http\Controllers;

use App\ItemComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\SpotComment;
use App\Spot;
class SpotCommentController extends Controller
{

//===============================================================================
//観光地コメント
//===============================================================================
    public function PostSpotComment(Request $request){
        $comment = $request->validate(['comment' => 'regex:/^[0-9a-zA-Z０-９ぁ-んァ-ヶー一-龠！？]+$/']);
        $comment = implode($comment);

        SpotComment::insert([
            'spotid' => $request->spotid,
            'userid' => $request->userid,
            'evaluation' => $request->evaluation,
            'comment' => $comment,
            'created_at' => now()
        ]);


        $spotid = $request->spotid;

        $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
            ->where('spots.id', $spotid)
            ->select('spots.id as id',
                'spots.name as spotname',
                'spots.article',
                'spots.url',
                'spots.tel',
                'users.name as creatername',
                'spots.post',
                'spots.add1',
                'spots.add2',
                'spots.createrid',
                'spots.created_at',
                'spots.updated_at')
            ->get();

        $spotcomments = SpotComment::join('users', 'users.id', '=', 'spot_comments.userid')
            ->where('spotid', $spotid)
            ->select('spot_comments.evaluation', 'spot_comments.comment', 'spot_comments.view', 'users.name')
            ->get();

        $evaluation = SpotComment::where('spotid', $spotid)
            ->avg('evaluation');

        return view('/Detail_Article', compact('spots', 'spotcomments', 'evaluation', 'createrid'));


    }



//===============================================================================
//管理者側一覧
//===============================================================================
    public function search(Request $request){

        $vkeyword = $request->validate(['keyword' => 'regex:/^[0-9a-zA-Z０-９ぁ-んァ-ヶー一-龠]+$/']);
        $vkeyword = implode($vkeyword);

        $spotcomments = SpotComment::join('users','users.id','=','spot_comments.userid')
            ->join('spots','spots.id','=','spot_comments.spotid')
            ->where('spot_comments.id','like','%'.$vkeyword.'%')
            ->select('spot_comments.id as id',
                'spot_comments.comment as comment',
                'spot_comments.evaluation as evaluation',
                'spot_comments.view as view',
                'spot_comments.created_at as created_at',
                'spot_comments.updated_at as updated_at',
                'users.name as name',
                'spots.name as spotname'
                )
            ->paginate(10);

        return view('/admin/All_SpotComment',compact('spotcomments'));
    }


//===============================================================================
//管理者側Viewの変更
//===============================================================================
    public function viewedit(Request $request){

    }

//===============================================================================
//
//===============================================================================
    //public function (){
    //
    //}
}
