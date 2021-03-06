<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
//======================================================================================================================
//
//======================================================================================================================
    public function view(Request $request)
    {
        $addresses = Address::where('userid', $request->userid)
            ->orderBy('addresses.id', '1')
            ->paginate(10);

        $message = null;
        $authsec = 0;

        return view('/All_Address', compact('addresses', 'message', 'authsec'));
    }




//======================================================================================================================
//
//======================================================================================================================
    public function userdetail(Request $request)
    {
        $addresses = Address::where('id', $request->id)->get();
        $authsec = 0;
        return view('/Edit_Address', compact('addresses', 'authsec'));
    }


//======================================================================================================================
//
//======================================================================================================================
    public function save(Request $request)
    {

        $request->validate([
            'toname' => 'required|max:30|regex:/^[a-zA-Z0-9a-zA-Z０-９ぁ-んァ-ヶー一-龠]+$/',
            'post' => 'required|min:7|max:7|regex:/^[0-9]+$/',
            'add1' => 'required|max:50|regex:/^[ぁ-んァ-ヶー一-龠]+$/',
            'add2' => 'required|max:50|regex:/^[a-zA-Z0-9ａ-ｚＡ-Ｚ０-９ぁ-んァ-ヶー一-龠]+$/'
        ]);

        Address::insert([
            'userid' => $request->userid,
            'toname' => $request->toname,
            'post' => $request->post,
            'add1' => $request->add1,
            'add2' => $request->add2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($request->authsec <> 1) {
            $addresses = Address::where('userid', $request->userid)
                ->orderBy('addresses.id', '1')
                ->paginate(10);

            $message = '追加しました';

            return view('/All_Address', compact('addresses', 'message'));
        } elseif ($request->authsec == 1) {
            $CartItems = request()->session()->get("CART", []);
            $CartItemCnt = request()->session()->get("CARTCNT", []);
            $itemcnt = 0;

            foreach ($CartItems as $index => $items) {
                $itemcnt = $itemcnt + $CartItemCnt[$index];
            }

            if ($itemcnt > 0) {

                //アドレス指定
                $address = Address::where('userid', $request->userid)->get();
                $authsec = true;

                return view('/Register_Topost', compact('address', 'authsec'));
            }
        }
    }


//======================================================================================================================
//
//======================================================================================================================
    public function create(Request $request)
    {
        $authsec = $request->authsec;

        return view('/Register_Address', compact('authsec'));
    }



//======================================================================================================================
//
//======================================================================================================================
    public function userupdate(Request $request)
    {
        $address = Address::findOrFail($request->id);
        $chg = false;
        $message = null;

        if ($request->toname <> null) {
            $vtoname = $request->validate(['toname' => 'required|max:30|regex:/^[a-zA-Zａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vtoname = implode($vtoname);
            $address->toname = $vtoname;
            $chg = true;
        }

        if ($request->post) {
            $vpost = $request->validate(['post' => 'required|min:7|max:7|regex:/^[0-9]+$/']);
            $vpost = implode($vpost);
            $address->post = $vpost;
            $chg = true;
        }

        if ($request->add1) {
            $vadd1 = $request->validate(['add1' => 'required|max:50|regex:/^[0-9０-９a-zA-Zａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vadd1 = implode($vadd1);
            $address->add1 = $vadd1;
            $chg = true;
        }

        if ($request->add2) {
            $vadd2 = $request->validate(['add2' => 'required|max:50|regex:/^[0-9０-９a-zA-Zａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vadd2 = implode($vadd2);
            $address->add2 = $vadd2;
            $chg = true;
        }

        if ($chg == true) {
            $address->updaterid = $request->userid;
            $address->updated_at = now();
            $address->save();
            $message = '更新しました';
        }

        $addresses = Address::where('userid', $request->userid)
            ->orderBy('id', '1')
            ->paginate(10);
        $user = User::where('id', $request->userid)->value('rank');

        if ($user == '1') {
            return view('/admin/All_Address', compact('addresses', 'message'));
        }

        return view('/All_Address', compact('addresses', 'message'));

    }


//======================================================================================================================
//
//======================================================================================================================
    public function adminview()
    {
        $addresses = Address::join('users', 'users.id', '=', 'addresses.userid')
            ->select([
                'addresses.id',
                'addresses.userid as userid',
                'addresses.toname',
                'addresses.post',
                'addresses.add1',
                'addresses.add2',
                'addresses.updaterid',
                'addresses.created_at',
                'addresses.updated_at',
                'users.name'
            ])
            ->orderBy('addresses.id', '1')
            ->paginate(10);

        $message = null;

        return view('/admin/All_Address', compact('addresses', 'message'));
    }


//======================================================================================================================
//
//======================================================================================================================
    public function search(Request $request)
    {
        $message = null;
        $addresses = Address::join('users', 'users.id', '=', 'addresses.userid')
            ->select([
                'addresses.id',
                'addresses.userid as userid',
                'addresses.toname',
                'addresses.post',
                'addresses.add1',
                'addresses.add2',
                'addresses.updaterid',
                'addresses.created_at',
                'addresses.updated_at',
                'users.name'
            ])
            ->orderBy('addresses.id', '1')
            ->paginate(10);

        if ($request->keyword) {

            $vkeyword = $request->validate(['keyword' => 'regex:/^[0-9a-zA-Zａ-ｚＡ-Ｚ０-９ぁ-んァ-ヶー一-龠]+$/']);
            $vkeyword = implode($vkeyword);


            if ($request->clumn == 'name') {
                $addresses = Address::join('users', 'users.id', '=', 'addresses.userid')
                    ->select([
                        'addresses.id',
                        'addresses.userid as userid',
                        'addresses.toname',
                        'addresses.post',
                        'addresses.add1',
                        'addresses.add2',
                        'addresses.updaterid',
                        'addresses.created_at',
                        'addresses.updated_at',
                        'users.name'
                    ])
                    ->where('users.' . $request->clumn, 'like', '%' . $vkeyword . '%')
                    ->orderBy('addresses.id', '1')
                    ->paginate(10);

            } else {
                $addresses = Address::join('users', 'users.id', '=', 'addresses.userid')
                    ->select([
                        'addresses.id',
                        'addresses.userid as userid',
                        'addresses.toname',
                        'addresses.post',
                        'addresses.add1',
                        'addresses.add2',
                        'addresses.updaterid',
                        'addresses.created_at',
                        'addresses.updated_at',
                        'users.name'
                    ])
                    ->where('addresses.' . $request->clumn, 'like', '%' . $vkeyword . '%')
                    ->orderBy('addresses.id', '1')
                    ->paginate(10);
            }
        }

        return view('/admin/All_Address', compact('addresses', 'message'));
    }





//======================================================================================================================
//
//======================================================================================================================
    public function detail(Request $request)
    {
        $message = null;

        $addresses = Address::join('users', 'users.id', '=', 'addresses.userid')
            ->where('addresses.id', $request->id)
            ->select([
                'addresses.id',
                'addresses.userid as userid',
                'addresses.toname',
                'addresses.post',
                'addresses.add1',
                'addresses.add2',
                'addresses.updaterid',
                'addresses.created_at as created_at',
                'addresses.updated_at as updated_at',
                'users.name'
            ])->get();

        $updatername = Address::join('users', 'users.id', '=', 'addresses.updaterid')
            ->distinct('users.name')->get();

        return view('/admin/Edit_Address', compact('addresses', 'updatername', 'message'));
    }





//======================================================================================================================
//
//======================================================================================================================
    public function update(Request $request)
    {
        $address = Address::findOrFail($request->id);
        $chg = false;
        $message = null;

        if ($request->toname <> null) {
            $vtoname = $request->validate(['toname' => 'required|max:30|regex:/^[a-zA-Zａ-ｚA-Zぁ-んァ-ヶー一-龠]+$/']);
            $vtoname = implode($vtoname);
            $address->toname = $vtoname;
            $chg = true;
        }


        if ($request->post <> null) {
            $vpost = $request->validate(['post' => 'required|min:7|max:7|regex:/^[0-9]+$/']);
            $vpost = implode($vpost);
            $address->post = $vpost;
            $chg = true;
        }


        if ($request->add1 <> null) {
            $vadd1 = $request->validate(['add1' => 'required|max:50|regex:/^[ぁ-んァ-ヶー一-龠]+$/']);
            $vadd1 = implode($vadd1);
            $address->add1 = $vadd1;
            $chg = true;
        }


        if ($request->add2 <> null) {
            $vadd2 = $request->validate(['add2' => 'required|max:50|regex:/^[a-zA-Z0-9ぁ-んァ-ヶー一-龠]+$/']);
            $vadd2 = implode($vadd2);
            $address->add2 = $vadd2;
            $chg = true;
        }

        if ($chg == true) {
            $address->updaterid = $request->userid;
            $address->updated_at = now();
            $address->save();
            $message = '更新しました';
        }


        $addresses = Address::join('users', 'users.id', '=', 'addresses.userid')
            ->where('addresses.id', $request->id)
            ->select([
                'addresses.id',
                'addresses.userid as userid',
                'addresses.toname',
                'addresses.post',
                'addresses.add1',
                'addresses.add2',
                'addresses.updaterid',
                'addresses.created_at as created_at',
                'addresses.updated_at as updated_at',
                'users.name'
            ])->get();


        $updatername = Address::join('users', 'users.id', '=', 'addresses.updaterid')
            ->distinct('users.name')->get();

        return view('/admin/Edit_Address', compact('addresses', 'updatername', 'message'));

    }

//======================================================================================================================
//
//======================================================================================================================
    //public function (){
    //
    //}
}
