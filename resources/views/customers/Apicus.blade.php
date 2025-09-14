@extends('layouts.master') {{-- أو حسب التصميم اللي عندك --}}

@section('content')
<div class="container">
    <h3>بيانات المشترك</h3>
    
    <!-- الصورة والاسم -->
    <img id="selfie" style="width:140px;height:140px;border-radius:10px;object-fit:cover">
    <div id="fullname" style="margin-top:10px;font-weight:bold"></div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $.get('/api/customers/15/card-data', function(res){
    if(res.status){
      $('#fullname').text(res.customer.fullname || '');
      if(res.photo){ 
        $('#selfie').attr('src', res.photo); 
      }
    } else {
      alert('ما قدرناش نجيب البيانات');
    }
  });
</script>
@endsection
