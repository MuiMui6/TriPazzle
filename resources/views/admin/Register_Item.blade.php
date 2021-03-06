@extends('layouts.notapp')

@section('content')

    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <div class="m-3">
                    <a href="/admin/All_Item">All Itemへ戻る</a>
                </div>
                <div class="col-12 m-3">
                    <h3 class="text-center">Register Item</h3>
                    <p class="text-center">商品登録</p>
                </div>
                <div class="m-3">
                    <h5>I can register the information about non-registration product.</h5>
                    <p>未登録商品に関する情報を登録することが出来ます。</p>
                </div>

                <form action="/admin/Register_Item" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <th class="text-center">Image</th>
                            <th class="text-left">
                                <p>
                                    <input type="file" name="img" enctype="multipart/form-data">
                                </p>
                                <p>画像ファイル( jpg / png / bmp / gif / svg )のみしか登録できません。</p>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="name" enctype="multipart/form-data">
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Profile</th>
                            <th class="text-center">
                                <textarea class="form-control" name="profile" rows="10"></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Price</th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="price">
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Size</th>
                            <th class="text-center">
                                <div class="form-group">
                                    <select class="form-control" id="exampleFormControlSelect1" name="sizeid">
                                        @foreach($sizes as $size)
                                            <option name="sizeid" value="{{$size->id}}">{{$size->height}}
                                                　mm　×　{{$size->width}} mm
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Peas</th>
                            <th class="text-center">
                                <div class="form-group">
                                    <select class="form-control" id="exampleFormControlSelect1" name="peasid">
                                        @foreach($peases as $peas)
                                            <option name="peasid" value="{{$peas->id}}">{{$peas->cnt}}　Peas</option>
                                        @endforeach
                                    </select>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th class="text-center">Spot Tag</th>
                            <th class="text-center">
                                <div class="form-group">
                                    <input type="text" class="form-inline form-control m-3" name="tag1">
                                    <input type="text" class="form-inline form-control m-3" name="tag2">
                                    <input type="text" class="form-inline form-control m-3" name="tag3">
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th class="text-center">View</th>
                            <th class="text-center">
                                <input type="radio" value="1" name="view"><span class="m-3 mr-5">Can View</span>
                                <input type="radio" value="0" name="view" checked><span
                                        class="m-3">Can't View</span>
                            </th>
                        </tr>

                        <tr>
                            <th></th>
                            <th>
                                <input type="hidden" value="{{Auth::user()->id}}" name="userid">
                                <button class="btn btn-primary btn-block">保存</button>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection