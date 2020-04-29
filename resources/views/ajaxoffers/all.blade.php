@extends('layouts.app')

@section('content')

    <div class="alert alert-success" id="success_msg" style="display: none;">
        تم الحذف بنجاح
    </div>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">{{__('messages.Offer Name')}}</th>
        <th scope="col">{{__('messages.Offer Price')}}</th>
        <th scope="col">{{__('messages.Offer details')}}</th>
        <th scope="col">صوره العرض</th>
        <th scope="col">{{__('messages.operation')}}</th>
    </tr>
    </thead>

    <tbody>
    @foreach($offers as $offer)
        <tr class="offerRow{{ $offer->id }}">
            <th scope="row">{{ $offer->id }}</th>
            <td>{{ $offer->name }}</td>
            <td>{{ $offer->price }}</td>
            <td>{{ $offer->details }}</td>
            <td><img  style="width: 90px; height: 90px;" src="{{asset('images/offers/'.$offer->photo)}}"></td>
            <td>
                <a href="{{url('offers/edit/'.$offer->id)}}" class="btn btn-success" value="{{$offer->id}}">{{__('messages.update')}}</a>
                <a href="{{route('offers.delete',$offer->id)}}" class="btn btn-danger" value="{{$offer->id}}">{{__('messages.delete')}}</a>
                <a href="" offer_id="{{$offer->id}}" class="delete_btn btn btn-danger">حذف اجاكس</a>
                <a href="{{route('ajax.offers.edit',$offer->id)}}" class=" btn btn-success">تعديل</a>
            </td>
        </tr>
    @endforeach
    </tbody>

</table>

@stop


@section('scripts')
    <script>

        $(document).on('click','.delete_btn',function (e) { // means if he click the form button with class delete_btn, run the ajax // note we used class instead of id because we are looping up and the id can't be looped (it will have same value)
            e.preventDefault(); // we added 'e' to pass as a variable to function to prevent for ex. user from going to another link before updating the ajax ! Note:this deprecated

            var offer_id = $(this).attr('offer_id');  //to bring the id value (which inserted in the attribute offer_id in the form), from the button which has id= delete_btn

            $.ajax({
                type        : 'post',
                url         : "{{route('ajax.offers.delete')}}",
                data        : {
                    '_token':"{{csrf_token()}}",
                    'id'    : offer_id
                },

                success: function (data) {  //note: whether saved or not it will return a message
                    if(data.status == true){
                        $('#success_msg').show(); // to show the hidden div above
                        // alert(data.msg) //to show alert

                        $('.offerRow'+data.id).remove();  // means selecting the class (.offerRow'+data.id) and then removing that line // '+' is to append in JS
                    }

                }, error:function (reject) {

                }

            });
        });


        //Old ajax format for inserting
        {{--$(document).on('click','#save_offer',function (e) { // means if he click the form button id, run the ajax--}}
        {{--    e.preventDefault(); // we added 'e' to pass as a variable to function to prevent for ex. user from going to another link before updating the ajax ! Note:this deprecated--}}
        {{--    $.ajax({--}}
        {{--        type: 'post',--}}
        {{--        url : "{{route('ajax.offers.store')}}",--}}
        {{--        data: {--}}
        {{--            '_token'     : "{{csrf_token()}}",  --}}{{-- we are passing the token and other form data because the ajax was not able to read and pass it from the form above to the store method --}}
        {{--                --}}{{--    'photo'      : $("input[name='photo']").val(),  --}}
        {{--            'name_ar'    : $("input[name='name_ar']").val(), //this will bring the value (syntax to bring input value using jquery)--}}
        {{--            'name_en'    : $("input[name='name_en']").val(),--}}
        {{--            'price'      : $("input[name='price']").val(),--}}
        {{--            'details_ar' : $("input[name='details_ar']").val(),--}}
        {{--            'details_en' : $("input[name='details_en']").val(),--}}

        {{--        },--}}
        {{--        success: function (data) {--}}

        {{--        }, error:function (reject) {--}}

        {{--        }--}}

        {{--    });--}}
        {{--});--}}

    </script>
@stop
