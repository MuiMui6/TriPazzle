@extends('layouts.notapp')

@section('content')

    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">

                <div class="col-12 m-3">
                    <h3 class="text-center">All Peas</h3>
                </div>
                <div class="col-lg-12 m-3">
                    <h5>ピース数に関する情報を作成・編集できます。</h5>
                </div>

                <div class="col-lg-12 m-3">
                    <form action="/admin/Register_Item" method="get">
                        <button class="btn btn-danger">
                           New Register Item
                        </button>
                    </form>
                </div>

                <div class="col-lg-12 m-3">
                    <form action="/admin/All_Peas" method="get">
                        <div class="input-group mr-3">
                            <input type="text" class="form-control"
                                   placeholder=" height / width / Creater Name / Updater Name "
                                   aria-describedby="button-addon2" name="keyword">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search!
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-12 m-3">

                    <div class="d-flex border-bottom border-dark">
                        <div class="col text-center"><h4>Item Name</h4></div>
                        <div class="col text-center"><h4>Size</h4></div>
                        <div class="col text-center"><h4>Peas</h4></div>
                        <div class="col text-center"><h4>View</h4></div>
                        <div class="col text-center"><h4>CreateDate</h4></div>
                        <div class="col text-center"><h4>UpdateDate</h4></div>
                        <div class="col text-center"><h4>Edit</h4></div>
                    </div>

                    @foreach($items as $item)

                        <form action="/admin/Edit_Item" method="get">
                            @csrf
                            <div class=" border-bottom">
                                <div class="d-flex bd-highlight m-3">
                                    <div class="col text-center">{{$item->itemname}}</div>
                                    <div class="col text-center">{{$item->height}}×{{$item->width}}</div>
                                    <div class="col text-center">{{$item->cnt}}</div>
                                    <div class="col text-center">
                                        @if($item->view == 0)
                                            <div class="alert alert-success">
                                                Can View
                                            </div>
                                        @elseif($item->view == 1)
                                            <div class="alert alert-danger">
                                                Can't View
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col text-center">{{$item->created_at->format('Y年m月d日')}}</div>
                                    <div class="col text-center">{{$item->updated_at->format('Y年m月d日')}}</div>
                                    <div class="col text-center">
                                        <input type="hidden" value="{{$item->id}}" name="id">
                                        <button class="btn btn-primary">
                                            EDIT
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    @endforeach

                </div>

                <div class="col-lg-12 m-3">
                    {!! $items->appends(Request::query())->links() !!}
                </div>

            </div>
        </div>
    </div>
@endsection