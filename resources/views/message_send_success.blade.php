@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
            <form action="/sendMessage" method="post" class="row" enctype="multipart/form-data">

                <?php
                    $check = 'not defined';
                ?>
                @csrf
                <div class="p-3 border rounded bg-light">
                    {{-- <div class="col-md-12">
                        <table class="table table-bordered mb-5">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="CheckAll" checked class="check" /> <label for="CheckAll"><span id="ReplaceCheckAll">Check</span> all</label>
                                </th>
                                <th scope="col">氏名</th>
                                <th scope="col">メールアドレス</th>
                                <th scope="col">住所</th>
                                <th scope="col">大学名</th>
                                <th scope="col">卒業年</th>
                                <th scope="col">性別</th>
                                <th scope="col">常勤</th>
                                <th scope="col">非常勤</th>
                                <th scope="col">スポット</th>
                                <th scope="col">健診</th>
                            </tr>
                            </thead>
                        </table>
                    </div> --}}
                    <div class="col-md-12 overflow-auto mb-3" style="height:300px;">
                        <table class="table table-hover table-bordered mb-5 sorted_records">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input type="checkbox" id="CheckAll" checked class="check" /> <label for="CheckAll"><span id="ReplaceCheckAll">Check</span> all</label>
                                    </th>
                                    <th scope="col">氏名</th>
                                    {{-- <th scope="col">メールアドレス</th> --}}
                                    <th scope="col">住所</th>
                                    <th scope="col">大学名</th>
                                    <th scope="col">卒業年</th>
                                    <th scope="col">性別</th>
                                    <th scope="col">常勤</th>
                                    <th scope="col">非常勤</th>
                                    <th scope="col">スポット</th>
                                    <th scope="col">健診</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                @php
                                    $record->lastname = trim($record->lastname)
                                @endphp
                            <tr>
                                <td>
                                    <input onClick="checkbox_lastname()"checked type="checkbox" class="checkbox_all_rows" name="user_id[]" value="{{ $record->user_id }}">
                                    {{-- <input  type="checkbox" class="checkbox_all_rows" name="user_id[]" value="{{ $record->user_id }}.{{ $record->lastname }}"> --}}
                                </td>
                                @if($record->lastname)
                                <td>
                                    {{ sprintf("%s %s", $record->lastname, $record->firstname) }}
                                </td>
                                @else
                                <td>
                                    {{ sprintf("%s %s", $record->lastname_kana, $record->firstname_kana) }}
                                </td>
                                @endif
                                {{-- <td>{{ $record->email }}</td> --}}

                                <td>{{ $record->address1 }}</td>
                                <td>{{ $record->college_name }}</td>
                                <td>{{ $record->graduation_year }}</td>
                                <td>{{ $record->sex == '1'? '男性':'女性'}}</td>
                                <td>{{ $record->desired_working_j == '1'? 'Yes':'No'}}</td>
                                <td>{{ $record->desired_working_h == '1'? 'Yes':'No'}}</td>
                                <td>{{ $record->desired_working_s == '1'? 'Yes':'No'}}</td>
                                <td>{{ $record->desired_working_k == '1'? 'Yes':'No'}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div class="col-md-12 input-group mb-3 mt-3 p-0" id="counter">
                        <div class="initial_count" id="initial_count">{{ $records->count() }}</div>
                    </div>

                    {{-- <div class="col-md-12 mb-3 mt-3 p-0">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            歴史
                        </button>
                    </div> --}}


                    <div class="col-md-12 mb-3 mt-3 p-0">
                        <div class="d-flex row gx-6 p-0">
                            <div class="col">
                                <div class="p-3 border rounded bg-light">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">送信日時</th>
                                                    <th scope="col">メッセージ</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="overflow-auto message-block">
                                        <table class="table table-hover overflow-scroll">
                                            <tbody id="history_tab">
                                                @foreach ($histories as $data)
                                                <tr>
                                                    <td class="text-truncate">{{$data->created_at}}</td>
                                                    <td style="max-width: 150px;" class="previousMessages text-truncate">{{$data->message}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 border rounded bg-light">
                                    <label for="copied_message" class="form-label">メッセージ</label>
                                    <textarea rows="10" id="copied_message" readonly name="message" class="form-control col-md-12" placeholder="メッセージ本文."></textarea>
                                    <div class="d-flex justify-content-end mt-2">
                                        <div class="btn btn-warning" onclick="copyToEditor()">コピー <i class="fa-solid fa-copy"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 border rounded bg-light">
                                    <label for="message" class="form-label">メッセージ</label>
                                    <textarea type="text" id="message" name="message" class="form-control" rows="10" required></textarea>
                                    <p class="character_notify float-left"></p><p class="character_count"></p>
                                    <div class="d-flex justify-content-end mt-2">
                                        <div type="reset" class="btn btn-secondary pr-4" onclick="resetForm()">クリア <i class="fa-solid fa-eraser"></i></div>&nbsp;
                                        <button type="submit" class="btn btn-success">送信 <i class="fa-solid fa-circle-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
    </div>
    {{-- <div class="row">
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-3 p-md-5">

              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">メッセージ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="container">
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
                        <tr>
                            <td>2023-01-18</td>
                            <td style="max-width: 150px;" class="view_message_content text-truncate">過去1年分の記事を検索していただけます。複数のキーワードを指定するとともに、期間や面名などで絞り込むことができます。</td>
                            <td>成田 智之</td>
                            <td>4090</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" placeholder="" value="" class="float-left mx-1 form-control w-50">
                            <button type="button" class="btn btn-primary float-left">絞り込み</button>
                        </div>
                        <div class="col-md-6">
                            <input type="text" placeholder="4090" value="" class="float-left mx-1 form-control w-50">
                            <button type="button" class="btn btn-primary float-left">絞り込み</button>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <label class="mt-4" for="">メッセージ内容</label>
                    <textarea rows="10" id="view_message_content_show" readonly name="view_message_content_show" class="form-control col-md-12 mt-2" placeholder="メッセージ本文."></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">閉じる</button>
              </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('scripts')
    <script type="text/javascript" charset="SJIS">
// -------------------  Character Limit Setting ------------------------
        $('textarea#message').keypress(function(e) {
            var tval = $('textarea#message').val(),
                tlength = tval.length,
                set = 2000,
                remain = parseInt(set - tlength);
            $('p.character_count').text(remain);
            $('p.character_notify').text("Character(s) Remaining: ");
            if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
                $('textarea#message').val((tval).substring(0, tlength - 1))
            }
        });
// -------------------  Character Limit Setting ------------------------


        $(function() {
            // displaySelection();


            $(".check").on('click', function(event){
                if ($(event.target).is(":checked")) {


                    $("#ReplaceCheckAll").html("Uncheck");
                    $("input[type=checkbox]", this).removeAttr('checked');
                    $('.checkbox_all_rows').prop('checked', this.checked).change();
                } else {
                    $("#ReplaceCheckAll").html("Check");
                    $("input[type=checkbox]", this).attr("checked", true);
                    $('.checkbox_all_rows').prop('checked', this.checked).change();
                }

                displaySelection();
            });
        });

        $('#history_tab').find('tr').click( function(){
            $("#copied_message").val($(this).find(".previousMessages").text() ?? '');
        });


        $('#view_message').find('tr').click( function(){
            $("#view_message_content_show").val($(this).find(".view_message_content").text() ?? '');
        });

        function copyToEditor()
        {
            $('textarea[name=message]').val($("#copied_message").val());
        }

        function resetForm() {
            $("#message").val('');
        }

        function checkbox_lastname(){
            displaySelection();
        }

        function displaySelection()
        {
            var ids = [];
            $("input[name='user_id[]']:checked").each( function () {
                ids.push( $(this).val() );

            });
            // console.log(ids.length);
            $('#counter').html( $("input[name='user_id[]']:checked").length);

            $.ajax({
                type: "POST",
                data: { "ids": ids, _token: '{{ csrf_token() }}' },
                url: "{!! route('load.integrated_users.json') !!}",
                cache: false,
                async: false,
                error: function(error) {
                    $('.destination_table').empty('');
                },
                success: function(content) {
                    //  console.log(content);
                    $("#lastname_show").html(content);
                }
            });
        }

        function checkbox_copied_message(){
            var checkboxes = document.getElementsByName('lastname12[]');

            if (document.getElementById('r1').checked) {
            rate_value = document.getElementById('r1').value;
            }


            document.getElementById("lastname_show1").innerHTML = displayText;
        }

        function displayRadioValue() {
            var ele = document.getElementsByName('select_message');

            for(i = 0; i < ele.length; i++) {
                if(ele[i].checked)
                document.getElementById("result").innerHTML= "&nbsp;"+ele[i].value;
                // document.getElementById("result12").value= ""+ele[i].value;
            }
        }

        function displayRadioValue1() {
            var ele = document.getElementsByName('select_message1');

            for(i = 0; i < ele.length; i++) {
                if(ele[i].checked)
                document.getElementById("result1").innerHTML
                        = "&nbsp;"+ele[i].value;
            }
        }
    </script>
@endsection
<style>
    .blocks{
        float: left;
        height: 300px;
    }
    .receipt_name_label{
        margin: 0 40px 0 0;
    }
    .float-left{
        float: left;
    }
    a.margin-right,
    div.message_class label{
        margin: 0 10px 0 0;
    }
    table.table td,
    table.table th{
        width: 10%;
    }
    div.test div{
        float: left;
    }
    .button-sub-next{
        clear: both;
    position: relative;
    top: 20px;
    }
    .float-right{
        float: right;
    }
    div.message label{
        vertical-align: top;
        float: left;
        margin: 10px 0;
    }
    #header {
        background-color:black;
        color:white;
        text-align:center;
        padding:5px;
    }
    div.container11 {
        width: 100%;

    }
    section.container1{
        display: -webkit-flex; /* Safari */
        display: flex;
    }

    .displayInline{
        -webkit-flex: 1;  /* Safari 6.1+ */
        -ms-flex: 1;  /* IE 10 */
        flex: auto;
    }

    nav {
        border-right: 2px solid gray;
    }

    nav ul {
        list-style-type: none;
        padding-top: 5px;
    }

    nav ul a {
        text-decoration: none;
        line-height: 30px;
    }

    div#loadOnClick {
        float: right;
    }

    .displayOnClick{
        display: none;
    }
    label.header_name{
        font-size: 16px;
        font-weight: bold;
    }
    .scroll-auto{
        height: 300px;
        overflow: scroll;
    }
    .scroll-auto-message{
        height: 150px;
        overflow: scroll;
    }
    .scroll-auto-message input{
        margin: 0 10px 0 0;
    }
    .scroll-auto-message table td{
        width:50%
    }
    .scroll-auto-message table td button{
        padding: 3% 6%;
    }
    .copied_data textarea{
    /* margin: 20px 0 0 0; */
    }
    .copied_data label{
        margin: 0 0 20px 0;
    }
    div.result_display{
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #212529;
        background-color: #f8fafc;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        appearance: none;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        overflow: scroll;
        height: 100px;
    }
    .message_body{
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #212529;
        background-color: #f8fafc;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        appearance: none;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .message_body_large{
        height:244px;
    }
    .message_body_small{
        height:100px;
    }
    div.message-block{
        height: 263px;
    }
    table.sorted_records thead tr th{
        position: sticky; top: -1; z-index: 1;
        background:#eee;
    }
</style>
