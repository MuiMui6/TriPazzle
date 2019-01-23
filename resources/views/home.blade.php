@extends('layouts.app')

@section('content')
    <div class="justify-content-center m-3">
        {!! $item->appends(Request::query())->links() !!}
    </div>

    <div class="row cart-columns">

        @foreach( $item as $items )
            <div class="card m-2" style="width: 17rem;">
                <img class="card-img-top" src="img/{{$items -> name}}.jpg" width="150px">
                <div class="card-body">
                    <p class="card-text">
                    <table class="table table-borderless">
                        <tbody>
                        {{--商品名--}}
                        <tr>
                            <th scope="row">Name</th>
                            <td>{{$items -> name}}</td>
                        </tr>

                        {{--紹介文--}}
                        <tr>
                            <th scope="row">Profile</th>
                            <td>{{$items -> profile}}</td>
                        </tr>

                        {{--サイズ--}}
                        <tr>
                            <th scope="row">Size</th>
                            <td>{{$items->height}}×{{$items->width}}（mm）</td>
                        </tr>

                        {{--ピース数--}}
                        <tr>
                            <th scope="row">PeasCnt</th>
                            <td>{{$items->cnt}}peas</td>
                        </tr>

                        {{--詳細--}}
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <form action="/Detail" method="get">
                                    @csrf
                                    <input type="hidden" value="{{$items->id}}" name="itemid">
                                    <input type="submit" class="btn btn-info" value="Detail">
                                </form>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="justify-content-center m-3">
        {!! $item->appends(Request::query())->links() !!}
    </div>

@endsection
