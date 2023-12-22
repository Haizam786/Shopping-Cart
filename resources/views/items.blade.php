@extends('layout')

@section('title', 'Items')

@section('content')

    <div class="container products">

        <span id="status"></span>

        <div class="row">

            @foreach($items as $item)
                <div class="col-xs-18 col-sm-6 col-md-3">
                    <div class="thumbnail">
                        <img src="{{ $item->photo }}" width="500" height="300">
                        <div class="caption">
                            <h4>{{ $item->name }}</h4>
                            <p>{{ \Illuminate\Support\Str::limit(strtolower($item->description), 50) }}</p>
                            <p><strong>Price: </strong>Rs.{{ $item->price }}</p>
                            <p class="btn-holder"><a href="javascript:void(0);" data-id="{{ $item->id }}" class="btn btn-warning btn-block text-center add-to-cart" role="button">Add to cart</a>
                                <i class="fa fa-circle-o-notch fa-spin btn-loading" style="font-size:24px; display: none"></i>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div><!-- End row -->

    </div>

@endsection

@section('scripts')

    <script type="text/javascript">
        $(".add-to-cart").click(function (e) {
            e.preventDefault();

            var ele = $(this);

            ele.siblings('.btn-loading').show();

            $.ajax({
                url: "{{ url('add-to-cart') }}" + '/' + ele.attr("data-id"),
                method: "get",
                data: {_token: '{{ csrf_token() }}'},
                dataType: "json",
                success: function (response) {

                    ele.siblings('.btn-loading').hide();

                    $("span#status").html('<div class="alert alert-success">'+response.msg+'</div>');
                    $("#header-bar").html(response.data);
                }
            });
        });
    </script>

@stop