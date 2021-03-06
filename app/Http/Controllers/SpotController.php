<?php

namespace App\Http\Controllers;

use App\Spot;
use App\SpotComment;
use App\User;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{

//テンプレート
//===============================================================================
//
//===============================================================================
    public function search(Request $request)
    {

        $vkeyword = $request->validate(['keyword' => 'regex:/^[0-9a-zA-Z０-９ぁ-んァ-ヶー一-龠]+$/']);
        $vkeyword = implode($vkeyword);

        $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
            ->select('spots.id as id', 'spots.name as spotname', 'spots.image', 'spots.article', 'spots.image as image')
            ->where('spots.name', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.article', 'like', '%' . $vkeyword . '%')
            ->orwhere('users.name', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.post', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.add1', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.add2', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.tag1', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.tag2', 'like', '%' . $vkeyword . '%')
            ->orwhere('spots.tag3', 'like', '%' . $vkeyword . '%')
            ->where('spots.view', '1')
            ->orderBy('spots.id', '1')
            ->paginate(9);


        if($request->key_tag <> null){
            $key_tag = $request->key_tag;

            $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
                ->select('spots.id as id', 'spots.name as spotname', 'spots.image', 'spots.article', 'spots.image as image')
                ->where('spots.name', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.article', 'like', '%' . $key_tag . '%')
                ->orwhere('users.name', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.post', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.add1', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.add2', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.tag1', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.tag2', 'like', '%' . $key_tag . '%')
                ->orwhere('spots.tag3', 'like', '%' . $key_tag . '%')
                ->where('spots.view', '1')
                ->orderBy('spots.id', '1')
                ->paginate(9);
        }

        //タグ
        $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

        return view('/SpotIndex', compact('spots','tag'));

    }

//===============================================================================
//
//===============================================================================
    public function detail(Request $request)
    {
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
                'spots.image as image',
                'spots.tag1 as tag1',
                'spots.tag2 as tag2',
                'spots.tag3 as tag3',
                'spots.createrid',
                'spots.created_at as created_at',
                'spots.updated_at as updated_at')
            ->get();


        $updater = Spot::join('users', 'users.id', '=', 'spots.updaterid')
            ->where('spots.id', $spotid)
            ->select('users.name as updatername')
            ->get();

        $spotcomments = SpotComment::join('users', 'users.id', '=', 'spot_comments.userid')
            ->where('spotid', $spotid)
            ->where('view', '1')
            ->select('spot_comments.evaluation', 'spot_comments.comment', 'spot_comments.view', 'users.name')
            ->get();

        $evaluation = SpotComment::where('spotid', $spotid)
            ->where('view', '1')
            ->avg('evaluation');


        //タグ
        $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

        return view('/Detail_Article', compact('spots', 'spotcomments', 'evaluation', 'createrid', 'updater','tag'));
    }

//===============================================================================
//
//===============================================================================
    public function newspot()
    {

        //タグ
        $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

        return view('/Register_Article',compact('tag'));
    }


//===============================================================================
//
//===============================================================================
    public function editspot(Request $request)
    {

        $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
            ->where('spots.id', $request->spotid)
            ->select('spots.id as id',
                'spots.name as spotname',
                'spots.article as article',
                'spots.createrid as userid',
                'spots.post as post',
                'spots.add1 as add1',
                'spots.add2 as add2',
                'spots.image as image',
                'spots.tag1 as tag1',
                'spots.tag2 as tag2',
                'spots.tag3 as tag3',
                'spots.url as url',
                'spots.tel as tel',
                'spots.view as view')
            ->get();

        $updater = Spot::join('users', 'users.id', '=', 'spots.updaterid')
            ->where('spots.id', $request->spotid)
            ->select('users.name as updatername')
            ->get();

        //タグ
        $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

        return view('/Edit_Article', compact('spots', 'updater','tag'));
    }


//===============================================================================
//
//===============================================================================
    public function save(Request $request)
    {

        //spotname
        $request->validate([
            'name' => ['regex:/^[a-zA-Z0-9ａ-ｚA-Z０-９ぁ-んァ-ヶー一-龠]+$/', 'min:2', 'max:30', 'required', 'string'],
            'article' => ['regex:/^[a-zA-Z0-9ａ-ｚA-Z０-９ぁ-んァ-ヶー一-龠＊！？・ー。、（）‐]+$/', 'min:10', 'max:500', 'required', 'string']
        ]);

        //登録
        $id = Spot::insertGetId([
            'name' => $request->name,
            'article' => $request->article,
            'post' => $request->post,
            'add1' => $request->add1,
            'add2' => $request->add2,
            'url' => $request->url,
            'tel' => $request->tel,
            'tag1' => $request->tag1,
            'tag2' => $request->tag2,
            'tag3' => $request->tag3,
            'createrid' => $request->userid,
            'created_at' => now(),
            'updaterid' => $request->userid,
            'updated_at' => now()
        ]);

        if ($request->hasFile('img')) {
            $spots = Spot::FindOrFail($id);
            $request->validate(['img' => 'image']);
            //画像登録
            $imgname = now()->format('Ymd') . '.jpg';
            Storage::makeDirectory('public/spots/' . $id);
            $request->file('img')->storeAs(
                'public/spots/' . $id, $imgname);
            $spots->image = $imgname;
            $spots->save();
        }

        $message = '追加しました';

        $user = User::where('id', $request->userid)->value('rank');

        if ($user == '0') {

            $spots = Spot::where('createrid', $request->userid)
                ->orderBy('created_at', '1')
                ->paginate(10);

            //タグ
            $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

            return view('/All_Article', compact('spots','message','tag'));
        } else {
            $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
                ->select('spots.id as id',
                    'spots.name as name',
                    'spots.article as article',
                    'spots.post as post',
                    'spots.add1 as add1',
                    'spots.add2 as add2',
                    'spots.view as view',
                    'spots.url as url',
                    'spots.tel as tel',
                    'users.name as creater',
                    'spots.created_at as created_at',
                    'spots.updated_at as updated_at'
                )
                ->orderBy('spots.id', '1')
                ->paginate(10);

            return view('/admin/All_Spot', compact('spots','message'));

        }
    }


//===============================================================================
//
//===============================================================================
    public function update(Request $request)
    {
        $spotid = $request->spotid;
        $spots = Spot::findOrFail($spotid);
        $chg = false;
        $message = null;

        //image
        if ($request->hasFile('img')) {
            $spots = Spot::FindOrFail($spotid);
            $request->validate(['img' => 'image']);
            //画像登録
            $imgname = now()->format('Ymd') . '.jpg';
            Storage::makeDirectory('public/spots/' . $spotid);
            $request->file('img')->storeAs(
                'public/spots/' . $spotid, $imgname);
            $spots->image = $imgname;
            $spots->save();
        }
        //spotname
        if ($request->name <> $spots->name && $request->name <> null) {
            $vname = $request->validate(['name' => 'required|min:2|max:30|string|regex:/^[a-zA-Z0-9ａ-ｚA-Z０-９ぁ-んァ-ヶー一-龠]+$/']);
            $vname = implode($vname);
            $spots->name = $vname;
            $chg = true;
        }

        //Article
        if ($request->article <> $spots->article && $request->article <> null) {
            $varticle = $request->validate(['article' => 'required|min:10|max:500|string|regex:/^[a-zA-Z0-9ａ-ｚＡ-Ｚ０-９ぁ-んァ-ヶー一-龠！？・ー。、（）]+$/']);
            $varticle = implode($varticle);
            $spots->article = $varticle;
            $chg = true;
        }

        //address_post
        if ($request->post <> $spots->post && $request->post <> null) {
            $vpost = $request->validate(['post' => 'nullable|max:7|regex:/^[0-9]+$/']);
            $vpost = implode($vpost);
            $spots->post = $vpost;
            $chg = true;
        }

        //address_add1
        if ($request->add1 <> $spots->add1 && $request->add1 <> null) {
            $vadd1 = $request->validate(['add1' => 'nullable|max:50|regex:/^[０-９ぁ-んァ-ヶー一-龠]+$/']);
            $vadd1 = implode($vadd1);
            $spots->add1 = $vadd1;
            $chg = true;
        }

        //address_add2
        if ($request->add2 <> $spots->add2 && $request->add2 <> null) {
            $vadd2 = $request->validate(['add2' => 'nullable|max:50|regex:/^[a-zA-Z0-9ａ-ｚA-Z０-９ぁ-んァ-ヶー一-龠-]+$/']);
            $vadd2 = implode($vadd2);
            $spots->add2 = $vadd2;
            $chg = true;
        }

        //url
        if ($request->url <> $spots->url && $request->url <> null) {
            $vurl = $request->validate(['url' => 'nullable|min:5|max:50|url|regex:/^[a-zA-Z0-9:/.]+$/']);
            $vurl = implode($vurl);
            $spots->name = $vurl;
            $chg = true;
        }


        //tel
        if ($request->tel <> $spots->tel && $request->tel <> null) {
            $vtel = $request->validate(['tel' => 'nullable|min:10|max:13|regex:/^[0-9]+$/']);
            $vtel = implode($vtel);
            $spots->tel = $vtel;
            $chg = true;
        }


        if ($request->tag1 <> null && $request->tag1 <> $spots->tag1) {
            $vtag = $request->validate(['tag1' => 'nullable|max:30|regex:/^[a-zA-Z0-9ａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vtag = implode($vtag);
            $spots->tag1 = $vtag;
            $chg = true;
        }


        if ($request->tag2 <> null && $request->tag2 <> $spots->tag2) {
            $vtag = $request->validate(['tag2' => '\'nullable|max:30|regex:/^[a-zA-Z0-9ａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vtag = implode($vtag);
            $spots->tag2 = $vtag;
            $chg = true;
        }

        if ($request->tag3 <> null && $request->tag3 <> $spots->tag3) {
            $vtag = $request->validate(['tag3' => 'nullable|max:30|regex:/^[a-zA-Z0-9ａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vtag = implode($vtag);
            $spots->tag3 = $vtag;
            $chg = true;
        }

        //view
        if ($request->view <> $spots->view && $request->view <> null) {
            $spots->view = $request->view;
            $chg = true;
        }


        if ($chg == true) {
            $spots->updated_at = now();
            $spots->updaterid = $request->userid;
            $spots->save();
            $message = '更新しました';
        }

        $user = User::where('id', $request->userid)->value('rank');
        if ($user == '0') {

            $spots = Spot::where('createrid', $request->userid)
                ->orderBy('created_at', '1')
                ->paginate(10);


            $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

            return view('/All_Article', compact('spots','tag'));
        } else {
            $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
                ->select('spots.id as id',
                    'spots.name as name',
                    'spots.article as article',
                    'spots.post as post',
                    'spots.add1 as add1',
                    'spots.add2 as add2',
                    'spots.view as view',
                    'spots.url as url',
                    'spots.tel as tel',
                    'users.name as creater',
                    'spots.created_at as created_at',
                    'spots.updated_at as updated_at'
                )
                ->orderBy('spots.id', '1')
                ->paginate(10);

            return view('/admin/All_Spot', compact('spots', 'message'));

        }
    }


//===============================================================================
//
//===============================================================================
    public function userarticle(Request $request)
    {

        $spots = Spot::where('createrid', $request->userid)
            ->orderBy('spots.id', '1')
            ->paginate(10);


        //タグ
        $tag = Tag::select('name')->where('genre', '2')->orwhere('genre', '3')->get();

        $message = null;

        return view('/All_Article', compact('spots', 'message','tag'));
    }


//===============================================================================
//
//===============================================================================
    public function adminview()
    {
        $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
            ->select('spots.id as id',
                'spots.name as name',
                'spots.article as article',
                'spots.post as post',
                'spots.add1 as add1',
                'spots.add2 as add2',
                'spots.view as view',
                'spots.url as url',
                'spots.tel as tel',
                'users.name as creater',
                'spots.created_at as created_at',
                'spots.updated_at as updated_at'
            )
            ->orderBy('spots.id', '1')
            ->paginate(10);

        $message = null;

        return view('/admin/All_Spot', compact('spots', 'message'));
    }


//===============================================================================
//
//===============================================================================
    public function adminsearch(Request $request)
    {

        $message = null;

        if ($request->keyword) {

            $vkeyword = $request->validate(['keyword' => 'regex:/^[0-9a-zA-Zａ-ｚＡ-Ｚ０-９ぁ-んァ-ヶー一-龠]+$/']);
            $vkeyword = implode($vkeyword);

            if ($request->clumn == 'creater') {

                $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
                    ->select('spots.id as id',
                        'spots.name as name',
                        'spots.article as article',
                        'spots.post as post',
                        'spots.add1 as add1',
                        'spots.add2 as add2',
                        'spots.view as view',
                        'spots.url as url',
                        'spots.tel as tel',
                        'users.name as creater',
                        'spots.created_at as created_at',
                        'spots.updated_at as updated_at'
                    )
                    ->where('users.name', 'like', '%' . $vkeyword . '%')
                    ->orderBy('spots.id', '1')
                    ->paginate(10);

            } else {

                $spots = Spot::join('users', 'users.id', '=', 'spots.createrid')
                    ->select('spots.id as id',
                        'spots.name as name',
                        'spots.article as article',
                        'spots.post as post',
                        'spots.add1 as add1',
                        'spots.add2 as add2',
                        'spots.view as view',
                        'spots.url as url',
                        'spots.tel as tel',
                        'users.name as creater',
                        'spots.created_at as created_at',
                        'spots.updated_at as updated_at'
                    )
                    ->where('spots.' . $request->clumn, 'like', '%' . $vkeyword . '%')
                    ->orderBy('spots.id', '1')
                    ->paginate(10);

            }

            return view('/admin/All_Spot', compact('spots', 'message'));
        }

        return redirect('/admin/All_Spot');

    }







//===============================================================================
//
//===============================================================================
    //public function (){
    //
    //}
}
