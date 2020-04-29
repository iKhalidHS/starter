@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="alert alert-success" id="success_msg" style="display: none;">
            تم التحديث بنجاح
        </div>

        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{ __('messages.Add your offer') }}
                </div>

                @if(Session::has('success')) {{-- to check if the session has a key 'success' --}}
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }} {{-- to get the session stored with key 'success' --}}
                </div>
                @endif
                <form method="POST" id="offerFormUpdate" action="" enctype="multipart/form-data"> {{-- = {{url('offers\store')}} // route('offers.store') is the name assigned to the routein web.php routes (not a direct url)  --}}

                    @csrf
                    {{-- <input name="_token" value="{{csrf_token()}}"> --}}

                    <div class="form-group">
                        <label for="exampleInputEmail1">اختر صورة العرض</label>
                        <input type="file" class="form-control" name="photo">
                        @error('photo')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <input type="text" style="display: none;" class="form-control" value="{{$offer->id}}" name="offer_id"> <!-- to send the id with the form to select using it in the update method-->

                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('messages.Offer Name ar') }}</label>
                        <input type="text" class="form-control" value="{{$offer->name_ar}}" name="name_ar" placeholder="Offer Name">
                        @error('name_ar')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('messages.Offer Name en') }}</label>
                        <input type="text" class="form-control" value="{{$offer->name_en}}" name="name_en" placeholder="Offer Name">
                        @error('name_en')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">{{ __('messages.Offer Price')}}</label>
                        <input type="text" class="form-control" value="{{$offer->price}}" name="price" placeholder="Price">
                        @error('price')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('messages.Offer details ar') }}</label>
                        <input type="text" class="form-control" value="{{$offer->details_ar}}" name="details_ar" placeholder="Details">
                        @error('details_ar')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('messages.Offer details en') }}</label>
                        <input type="text" class="form-control" value="{{$offer->details_en}}" name="details_en" placeholder="Details">
                        @error('details_en')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <button id="update_offer" class="btn btn-primary">Save Offer</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>

        $(document).on('click','#update_offer',function (e) { // means if he click the form button id, run the ajax
            e.preventDefault(); // we added 'e' to pass as a variable to function to prevent for ex. user from going to another link before updating the ajax ! Note:this deprecated

            var formData = new FormData($('#offerFormUpdate')[0]); //method in javascript to get all form data  // #offerFormUpdate is the form id

            $.ajax({
                type        : 'post',
                enctype     : 'multipart/form-data', //if we will upload file
                url         : "{{route('ajax.offers.update')}}",
                data        : formData,
                processData : false,
                contentType : false,
                cashe       : false,

                success: function (data) {  //note: whether saved or not it will return a message
                    if(data.status == true){
                        $('#success_msg').show(); // to show the hidden div above
                        // alert(data.msg) //to show alert
                    }

                }, error:function (reject) {
                }
            });
        });

    </script>
@stop
