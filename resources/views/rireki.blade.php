@extends('layouts.app')

@section('content')

@if (Route::has('login'))
    @if(Auth::user())
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="py-1 breadcrumb-wrapper mb-0">
                                履歴
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="row">
                                        <div class="p-3 border rounded bg-light">
                                            <div class="col-md-12 overflow-auto mb-3" style="height:300px;">
                                                <table class="table table-hover table-bordered mb-5 sorted_records">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">送信日時</th>
                                                            <th scope="col">メッセージ内容</th>
                                                            <th scope="col">名前</th>
                                                            <th scope="col">管理番号</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="view_message">
                                                        @foreach($histories as $data)
                                                        <tr>
                                                            <td scope="row">{{$data->created_at}}</td>
                                                            <td scope="row" style="max-width: 150px;" class="view_message_content text-truncate">{{ $data->message }}</td>
                                                            <td scope="row">{{ $data->family_name }}{!! "&nbsp;" !!}{{ $data->first_name }}</td>
                                                            <td scope="row">{{ $data->mgt_no }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="container mt-1">
                                            <table class="table table-bordered table-hover table-bordered mb-2 sorted_records">
                                                <thead>
                                                    <tr class="table-success">
                                                        <th scope="col">送信日時</th>
                                                        <th scope="col">メッセージ内容</th>
                                                        <th scope="col">名前</th>
                                                        <th scope="col">管理番号</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="overflow-auto message-block">
                                                <table class="table table-bordered table-hover overflow-scroll">
                                                    <tbody id="view_message">
                                                        @foreach($users as $data)
                                                        <tr>
                                                            <td scope="row">{{$data->created_at}}</td>
                                                            <td scope="row" style="max-width: 150px;" class="view_message_content text-truncate">{{ $data->message }}</td>
                                                            <td scope="row">{{ $data->family_name }}{!! "&nbsp;" !!}{{ $data->first_name }}</td>
                                                            <td scope="row">{{ $data->mgt_no }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row mt-3">
                                        <div class="col-md-6 d-flex" id="sandbox-container">
                                            <div class="input-group date col-md-8 p-0">
                                                <input name="submitted_at" type="text" class="form-control" placeholder="yyyy/mm/dd">
                                                <span class="input-group-addon input-group-text">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                </span>
                                            </div>
                                            <div id="DateSelected" class="ml-2  btn btn-primary rounded">絞り込み</div>
                                        </div>

                                        <div class="col-md-6 d-flex justify-content-end">
                                            <input name="mgt_no" type="number" placeholder="0000" value="" class="form-control col-6">
                                            <div id="mgtID" class="btn btn-primary rounded ml-2">絞り込み</div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <label class="mt-4" for="">メッセージ内容</label>
                                        <textarea rows="10" id="view_message_content_show" readonly name="view_message_content_show" class="form-control col-md-12 mt-2" placeholder="メッセージ本文."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

@endsection

@section('scripts')

<script type="text/javascript">


    $('#view_message').find('tr').click( function(event){
        $("#view_message_content_show").val($(this).find(".view_message_content").text() ?? '');
    });

    $('#sandbox-container .input-group.date').datepicker({
        weekStart: 1,
        language: "ja",
        orientation: "bottom auto",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true
    });

    $('#DateSelected').on('click',function(){
        var submitted_at = $("input[name='submitted_at']").val();
        if(submitted_at)
        {
            search_by({'submitted_at' : submitted_at});
        }
    });

    $('#mgtID').on('click',function(){
        var mgt_no = $("input[name='mgt_no']").val();
        if(mgt_no)
        {
            search_by({'mgt_no' : mgt_no});
        }
    });

    function table_post_row(res){
    let htmlView = '';
    if(res.messages.length <= 0){
        htmlView+= `
           <tr>
              <td colspan="4">No data.</td>
          </tr>`;
    }

    for(let i = 0; i < res.messages.length; i++){
        htmlView += `
            <tr>
                <td scope="row">`+res.messages[i].created_at+`</td>
                <td scope="row" style="max-width: 150px;" class="view_message_content text-truncate">`+res.messages[i].message+`</td>
                <td>`+res.messages[i].family_name+` `+res.messages[i].first_name+`</td>
                <td>`+res.messages[i].sender_id+`</td>
            </tr>`;
    }

         $('tbody#view_message').html(htmlView);

         $('#view_message').find('tr').click( function(event){
            $("#view_message_content_show").val($(this).find(".view_message_content").text() ?? '');
        });

    }

    function search_by(keywords){
         $.post('{{ route("message.search") }}', { _token: '{{ csrf_token() }}', keywords}, function(res){
            table_post_row(res);
        });
    }

</script>

@endsection
<style>
    div.message-block{
        height: 263px;
    }
    table.sorted_records thead tr th{
        position: sticky; top: -1; z-index: 1;
        background:#eee;
    }
</style>
