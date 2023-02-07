@extends('layouts.app')

@section('content')

@if (Route::has('login'))
    @if(Auth::user())
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="py-3 breadcrumb-wrapper mb-0">
                                <i class="fa-brands fa-line"></i> Line メール配信
                            </h4>
                        </div>
                        <div class="card-body">
                                {{-- <div>
                                    <button onclick="enable()">Message Send Active</button>
                                    <button onclick="disable()">Message Send Deactive</button>
                                </div> --}}

                                <b>&nbsp;</b>
                                <!-- Advance Search Modal -->
                                <div class="" tabindex="" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">

                                        <form action="/sendRequest" method="post" class="row" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="result_display">
                                                    <div class="mb-2 row">
                                                        <label class="col-md-2">
                                                            住所
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="col-md-8" id="pref_show"></div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-2" />
                                                    <div class="mt-2 row">
                                                        <label class="col-md-2">
                                                            性別
                                                        </label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="gender_show" name="gender_show" style="border: 0px; pointer-events: none;">
                                                        </div>
                                                    </div>
                                                    {{-- <hr class="my-2" />
                                                    <div class="mt-2 row">
                                                        <label class="col-md-2">
                                                            大学名
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div id="college_name_show"></div>
                                                        </div>
                                                    </div> --}}
                                                    <hr class="my-2" />
                                                    <div class="mt-2 row">
                                                        <label class="col-md-2">
                                                            卒業年
                                                        </label>
                                                        <div class="col-md-8">
                                                            <input type="number" id="grad_year_from2" name="grad_year_from2" class="border-0" value="" />
                                                            <input type="number" id="grad_year_to2" name="grad_year_to2" class="border-0"  value="" />
                                                        </div>
                                                    </div>
                                                    <hr class="my-2" />
                                                    <div class="mt-2 row">
                                                        <label class="col-md-2">
                                                            希望科目
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div id="kiboukamoku_show"></div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-2" />
                                                    <div class="mt-2 row">
                                                        <label class="col-md-2">
                                                            希望勤務
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div id="desired_work_style_show"></div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-2" />
                                                    <div class="mb-2 row">
                                                        <label class="col-md-2">
                                                            希望勤務地
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="col-md-8" id="preferred_work_place_show"></div>
                                                        </div>
                                                    </div>
                                                    <hr class="my-2" />
                                                </div>

                                                <div class="modal-body">
                                                    <div class="row mx-2">
                                                        <section id="container1">
                                                            <nav class="displayInLine" style="width: 24%; float: left;">
                                                            <ul>
                                                                <li><a href="#pref" class="quickLinks" onclick='loadContent("#pref")'>住所</a></li>
                                                                <li><a href="#gender" class="quickLinks" onclick='loadContent("#gender")'>性別</a></li>
                                                                {{-- <li><a href="#college_name" class="quickLinks" onclick='loadContent("#college_name")'>大学名</a></li> --}}
                                                                <li><a href="#grad_year" class="quickLinks" onclick='loadContent("#grad_year")'>卒業年</a></li>
                                                                <li><a href="#kiboukamoku" class="quickLinks" onclick='loadContent("#kiboukamoku")'>希望科目</a></li>
                                                                <li><a href="#desired_work_style" class="quickLinks" onclick='loadContent("#desired_work_style")'>希望勤務</a></li>
                                                                <li><a href="#preferred_work_place" class="quickLinks" onclick='loadContent("#preferred_work_place")'>希望勤務地</a></li>
                                                            </ul>
                                                            </nav>

                                                            <div class="displayInLine" id="loadOnClick" style="width:74%; float: right;">

                                                            </div>
                                                        </section>

                                                        <div id="pref" class="displayOnClick">
                                                            <label class="form-label header_name" for="pref">住所</label>
                                                            <div class="container">
                                                                <div class="col-md-12">
                                                                    @foreach($address1 as $row)
                                                                    <div class="col-md-2" style="float:left">
                                                                        <input onClick="checkbox_pref();" type="checkbox" id="{{$row->name}}" name="pref[]" value="{{$row->name}}">
                                                                        <label for="{{$row->name}}">{{$row->name}}</label>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="gender" class="displayOnClick">
                                                            <label class="form-label header_name" for="gender">性別</label>
                                                            <div class="container">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-2" style="float:left">
                                                                        <input class="genderinput" type="radio" id="male" name="gender" onclick="myFunction_gender(event)" value="1">
                                                                        <label for="html">男性</label><br>
                                                                        <input class="genderinput" type="radio" id="female" name="gender" onclick="myFunction_gender(event)" value="2">
                                                                        <label for="css">女性</label><br>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- <div id="college_name" class="displayOnClick">
                                                            <label class="form-label header_name" for="college_name">大学名</label>
                                                            <div class="container">
                                                                <div class="col-md-12 scroll-auto">
                                                                    @foreach($college_name as $row)
                                                                    <div class="col-md-4" style="float:left">
                                                                        <input onClick="checkbox_college_name();" type="checkbox" id="college_name" name="college_name[]"  value="{{$row->college_name}}">
                                                                        <label for="college_name">{{$row->college_name}}</label>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div> --}}


                                                        <div id="grad_year" class="displayOnClick">
                                                            <script type="text/javascript">
                                                                $(document).ready(function() {
                                                                    $('#grad_year_select_from').keyup(function(e) {
                                                                        var v = $('#grad_year_select_from').val();
                                                                        if(v) { $('#grad_year_from2').val(v) };
                                                                    });

                                                                    $('#grad_year_select_to').keyup(function(e) {
                                                                        var v = $('#grad_year_select_to').val();
                                                                        if(v) { $('#grad_year_to2').val(v) };
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="form-label header_name" for="grad_year">卒業年</label>
                                                            <div class="container">
                                                                <div class="mb-2 row">
                                                                    <div class="col-md-3">
                                                                        <input name="grad_year_select_from" id="grad_year_select_from" value="" class="form-control" placeholder="From" onkeypress="return validateNumber(event)" maxlength="4" />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input name="grad_year_select_to" id="grad_year_select_to" value="" class="form-control" placeholder="To" onkeypress="return validateNumber(event)" maxlength="4" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- <div id="grad_year" class="displayOnClick">
                                                            <label class="form-label header_name" for="grad_year">卒業年</label>
                                                            <div class="container">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-6" style="float: left;padding: 1%;">
                                                                        <label for="from">開始年</label>
                                                                        <select class="form-select" name="grad_year_from" id="grad_year_select_from">
                                                                            <?php
                                                                                $year = date('Y');
                                                                                $add = $year - 2012;
                                                                                $min = 1990 + $add;
                                                                                $max = $min + 22;
                                                                                for($i=$min;$i<=$max;$i++)
                                                                                {
                                                                                    echo '<option value='.$i.'>'.$i.'</option>';
                                                                                }
                                                                            ?>
                                                                            </select>
                                                                    </div>
                                                                    <div class="col-md-6" style="float: right;padding: 1%">
                                                                        <label for="to">終了年</label>
                                                                        <select class="form-select" name="grad_year_to" id="grad_year_select_to">
                                                                            <?php
                                                                                $year = date('Y');
                                                                                $add = $year - 2012;
                                                                                $min = 1990 + $add;
                                                                                $max = $min + 22;
                                                                                for($i=$min;$i<=$max;$i++)
                                                                                {
                                                                                    echo '<option value='.$i.'>'.$i.'</option>';
                                                                                }
                                                                            ?>
                                                                            </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}

                                                        <div id="kiboukamoku" class="displayOnClick">
                                                            <label class="form-label header_name" for="kiboukamoku">希望科目</label>
                                                            <div class="container">
                                                                <div class="container">
                                                                    <div class="col-md-12 scroll-auto">
                                                                        @foreach($kiboukamoku as $row)
                                                                        <div class="col-md-4" style="float:left">
                                                                            <input onClick="checkbox_kiboukamoku();" type="checkbox" id="kiboukamoku" name="kiboukamoku[]"  value="{{$row->name}}">
                                                                            <label for="kiboukamoku">{{$row->name}}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="desired_work_style" class="displayOnClick">
                                                            <label class="form-label header_name" for="desired_work_style">希望勤務</label>
                                                            <div class="container">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-5">
                                                                        @foreach($desired_work_styles as $row)
                                                                        <input onClick="checkbox_desired_work_style();" type="checkbox" id="desired_work_style" name="desired_work_style[]"  value="{{$row->name}}">
                                                                        <label for="desired_work_style">{{$row->name}}</label><br>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="preferred_work_place" class="displayOnClick">
                                                            <label class="form-label header_name" for="preferred_work_place">希望勤務地</label>
                                                            <div class="container">
                                                                <div class="col-md-12">
                                                                    @foreach($address1 as $row)
                                                                    <div class="col-md-2" style="float:left">
                                                                        <input onClick="checkbox_preferred_work_place();" type="checkbox" id="{{$row->name}}" name="preferred_work_place[]" value="{{$row->name}}">
                                                                        <label for="{{$row->name}}">{{$row->name}}</label>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 pt-4">
                                                            <button type="submit" class="btn btn-primary float-right">検索</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- End Advance Search Modal --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif


@endsection
	<style>
        .float-left{
            float: left;
        }
        .float-right{
            float: right;
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
	</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script type="text/javascript">
    function validateNumber(e) {
        const pattern = /^[0-9]$/;
            return pattern.test(e.key )
        }
</script>

<script type="text/javascript">
	function loadContent(selector){
		$("#loadOnClick").html($(selector).html());
	};
	$(document).ready(function(){
		loadContent("#pref");
    });
</script>
<script type="text/javascript">
    // Real Changes Value of Prefecture start ..............................................
    function checkbox_pref(){
    var checkboxes = document.getElementsByName('pref[]');

    var displayText = "";
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            // displayText += `<span>${checkboxes[i].value}</span><input name='pref[]' type='hidden' value=${checkboxes[i].value} />,`;
            displayText += `<span>${checkboxes[i].value}</span><input name='pref[]' type='hidden' value=${checkboxes[i].value} />,`;
            // displayText += `<span>, </span>`;
        }
    }
    // console.log(displayText);
    document.getElementById("pref_show").innerHTML = displayText;
    }
    // Real Changes Value of Prefecture end ..............................................

    // Real Changes Value of Gender start ...................................................
    function myFunction_gender(event) {
        document.getElementById("gender_show").value = event.target.value==1?'男性':'女性';
    }
    // Real Changes Value of Gender end ...................................................
    // Real Changes Value of College Name start ..............................................
    // function checkbox_college_name(){
    // var checkboxes = document.getElementsByName('college_name[]');

    // var checkboxesChecked = [];
    // for (var i=0; i<checkboxes.length; i++) {
    //     if (checkboxes[i].checked) {
    //         checkboxesChecked.push(checkboxes[i].value);
    //     }
    // }
    // document.getElementById("college_name_show").value = checkboxesChecked;

    // ------------------------
    // var displayText = "";
    // for (var i=0; i<checkboxes.length; i++) {
    //     if (checkboxes[i].checked) {
    //         displayText += `<span>${checkboxes[i].value}</span><input name='college_name[]' type='hidden' value=${checkboxes[i].value} />,`;
    //         // displayText += `<span>, </span>`;
    //     }
    // }
    // console.log(displayText);
    // <input name='college_name[]' type='hidden' value=${checkboxes[i].value} />
    // document.getElementById("college_name_show").innerHTML = displayText;
    // ------------------------

    // }
    // Real Changes Value of College Name end ..............................................

    // Real Changes Value of kiboukamoku start ..............................................
    function checkbox_kiboukamoku(){
    var checkboxes = document.getElementsByName('kiboukamoku[]');
    var displayText = "";
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            displayText += `<span>${checkboxes[i].value}</span><input name='kiboukamoku[]' type='hidden' value=${checkboxes[i].value} />,`;
        }
    }
    document.getElementById("kiboukamoku_show").innerHTML = displayText;
    }
    // Real Changes Value of kiboukamoku end ..............................................

    // Real Changes Value of preferred_work_place start ..............................................
    function checkbox_preferred_work_place(){
    var checkboxes = document.getElementsByName('preferred_work_place[]');
    var displayText = "";
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            displayText += `<span>${checkboxes[i].value}</span><input name='preferred_work_place[]' type='hidden' value=${checkboxes[i].value} />,`;
        }
    }
    document.getElementById("preferred_work_place_show").innerHTML = displayText;
    }
    // Real Changes Value of preferred_work_place end ..............................................


    // Real Changes Value of Graduation Year from start ..............................................
    $(document).on('change','#grad_year_select_from' ,function(){
    var val = $('#grad_year_select_from option:selected').val();
    var text = $('#grad_year_select_from option:selected').text();
    $('.grad_year_from').text(val);
    var test = $('#grad_year_select_from option:selected').text();
    // document.getElementById("myInput").value = test;

    })
    // Real Changes Value of Graduation Year from end ..............................................
    // Real Changes Value of Graduation Year to start ..............................................
    $(document).on('change','#grad_year_select_to' ,function(){
    var val = $('#grad_year_select_to option:selected').val();
    var text = $('#grad_year_select_to option:selected').text();
    $('.grad_year_to').text(val);
    var test1 = $('#grad_year_select_to option:selected').text();
    // document.getElementById("myInput1").value = test1;
    })
    // Real Changes Value of Graduation Year to end ..............................................
    // Real Changes Value of Desired Work Style start ..............................................
    function checkbox_desired_work_style(){
    var checkboxes = document.getElementsByName('desired_work_style[]');
        // console.log(checkboxes);

    // var checkboxesChecked = [];
    // for (var i=0; i<checkboxes.length; i++) {
    //     if (checkboxes[i].checked) {
    //         checkboxesChecked.push(checkboxes[i].value);
    //     }
    // }
    // document.getElementById("desired_work_style_show").value = checkboxesChecked;

    // ------------------------
    var displayText = "";
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            displayText += `<span>${checkboxes[i].value}</span>,`;

        }
    }
    // console.log(displayText);
    document.getElementById("desired_work_style_show").innerHTML = displayText;
    // ------------------------

    }
    // Real Changes Value of Desired Work Style end ..............................................
    </script>
