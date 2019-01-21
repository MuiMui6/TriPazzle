@extends('layouts.tripapp')

@section('content')

    <div class="row">
        <div class="card col-lg-12 text-center">
            <div class="card-body">

                <h2 class="card-title">Edit Article</h2>
                <img src="img/s_line.png">

                {{--新規作成--}}
                @foreach($spots as $spot)

                    <form action="/Update_Article" method="post">
                        @csrf
                        <table class="table">
                            <tr>
                                <th class="text-center">
                                    Photo
                                </th>
                                <th class="text-center">
                                    <input type="file" name="image">
                                </th>
                            </tr>


                            <tr>
                                <th class="text-center">
                                    Spot Name
                                </th>
                                <th class="text-center">
                                    <input type="text" class="form-control" name="name"
                                           placeholder="{{$spot->spotname}}">
                                </th>
                            </tr>


                            <tr>
                                <th class="text-center">
                                    Article
                                </th>
                                <th class="text-center">
                                    <textarea class="form-control" cols="50" rows="10" name="article"
                                              placeholder="{{$spot->article}}"></textarea>
                                </th>
                            </tr>


                            <tr>
                                <th class="text-center">
                                    Address
                                </th>
                                <th class="text-center">
                                    <div class="input-group m-3">
                                        <img src="img/post.png" class="mr-2" height="30px">
                                        <input type="text" class="form-control" name="post"
                                               placeholder="{{$spot->post}}">
                                    </div>
                                    <input type="text" class="form-control m-3" name="add1"
                                           placeholder="{{$spot->add1}}">
                                    <input type="text" class="form-control m-3" name="add2"
                                           placeholder="{{$spot->add2}}">
                                </th>
                            </tr>


                            <tr>
                                <th class="text-center">
                                    URL
                                </th>
                                <th class="text-center">
                                    <input type="url" class="form-control" name="url" placeholder="{{$spot->url}}">
                                </th>
                            </tr>


                            <tr>
                                <th class="text-center">
                                    TEL
                                </th>
                                <th class="text-center">
                                    <input type="tel" class="form-control" name="tel" placeholder="{{$spot->tel}}">
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    View
                                </th>
                                <th class="text-center">
                                    @if($spot->view == 1)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="view"
                                                   id="inlineRadio1"
                                                   value="1" checked>
                                            <label class="form-check-label" for="inlineRadio1">Can View</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="view"
                                                   id="inlineRadio2"
                                                   value="0">
                                            <label class="form-check-label" for="inlineRadio2">Can't View</label>
                                        </div>
                                    @else
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="view"
                                                   id="inlineRadio1"
                                                   value="1">
                                            <label class="form-check-label" for="inlineRadio1">Can View</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="view"
                                                   id="inlineRadio2"
                                                   value="0" checked>
                                            <label class="form-check-label" for="inlineRadio2">Can't View</label>
                                        </div>
                                    @endif
                                </th>
                            </tr>


                            <tr>
                                <th class="text-center">
                                    Creater Name
                                </th>
                                <th class="text-center">
                                    {{Auth::user()->name}}
                                </th>
                            </tr>

                        </table>

                        <button type="submit" class="btn btn-primary btn-block m-3">
                            <input type="hidden" value="{{$spot->id}}" name="spotid">
                            <input type="hidden" value="{{Auth::user()->id}}" name="userid">
                            Save
                        </button>
                        @endforeach
                    </form>
            </div>
        </div>
    </div>
@endsection