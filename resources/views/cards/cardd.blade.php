@extends('layouts.app')
@section('title',"البطاقة")

@section('content')
<style>
@media print {
   body  {
      -webkit-print-color-adjust: exact;
   }
}
    </style>
<script>

    function printContent(el){
      var restorepage = document.body.innerHTML;
      var printcontent = document.getElementById(el).innerHTML;
      document.body.innerHTML = printcontent;
      window.print();
      document.body.innerHTML = restorepage;
     }

		
    </script>
<div class="row">
    <div class="col-md-6"  align="center">
        <div id="my_camera" style="width:  3.4in; height:2.127in"></div>
        <button type="button" value="Take Snapshot" onClick="take_snapshot()" class="btn btn-primary waves-effect waves-light">التقاط</button>

    </div>
    {{-- /-------------------------------------------------------------------- --}}
    <div class="col-md-6">
  <div  id="gift"  align="center">

    <div  id="" style="width:  3.4in; height:2.127in; background-image : url(../card/front.jpg) !important; background-size :  3.4in 2.127in !important;"  dir="rtl">
        <div class="title-area text-left" id="results" style="margin-left: 50px;padding-top: 38px;">
            {{-- {!! QrCode::size(70)->color(255, 255, 255)->generate($customer->customers->regnumber); !!} --}}
            <img  style="border-radius:10px" width="70px" height="70px" src=""/>
        </div> 
        <div style="display: inline-grid;margin-top:0px;float: right;margin-right: 22px;">
            <label  id="fullnamea" name="fullnamea" style="font-size : 8px !important; font-weight: bold !important; color: #9b5549 !important; text-align: right;vertical-align: bottom;margin-bottom: 10px;margin-top: 9px;">
{{$customer->customers->fullnamea}}         
</label>
<label  id="yearbitrh" name="yearbitrh"style="font-size : 8px; font-weight: bold; color:#9b5549 !important ; text-align: right;vertical-align: bottom;margin-bottom: 6px;">
    {{$customer->customers->yearbitrh}}
    </label>
<label  id="warrantynumber" name="warrantynumber" style="font-size : 8px; font-weight: bold; color: #9b5549 !important ; text-align: right;vertical-align: bottom">
    {{$customer->warrantynumber}}
    </label>

    <label  id="warrantyoffices" name="warrantyoffices" style="font-size : 7px; font-weight: bold; color: #9b5549 !important ; text-align: right;vertical-align: bottom">
      {{$customer->customers->municipals->name}} -    {{$customer->warrantyoffices->name}}     
        </label>
        </div>
    </div>
    <div  id="" style="width:  3.4in; height:2.127in; background-image : url(../card/backend.jpg) !important ; background-size :  3.4in 2.127in !important;"  dir="rtl">

        <div class="title-area text-left" style="margin-left: 47px;padding-top: 34px;">

            {!! QrCode::size(75)->color(155, 85, 73)->generate($customer->customers->regnumber); !!}
        </div>        

        <div class="text-center" style="display: inline-grid !important ;margin-top: 8px  !important; ">
            <label  id="regnumber" name="regnumber" style="font-size : 18px !important; font-weight: bold ; color: #9b5549 !important ; vertical-align: bottom !important;margin-bottom: 8px !important;margin-bottom: 6px !important;margin-right: 91px;">
{{$customer->customers->regnumber}}         
</label>
        </div>
    </div>
    
</div>
<br>
<div class="text-center">
<button type="button"  onclick="printContent('gift')" class="btn btn-primary waves-effect waves-light">طباعة</button>
</div>

    </div>

</div>
<script language="JavaScript">
    Webcam.set({
			width: 400,
			height: 400,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
		Webcam.attach('#my_camera' );
    function take_snapshot() {
        // take snapshot and get image data
        Webcam.snap( function(data_uri) {
            // display results in page
            document.getElementById('results').innerHTML = 
                '<img  style="border-radius:10px" width="70px" height="70px" src="'+data_uri+'"/>';
        } );
    }
</script>
@endsection