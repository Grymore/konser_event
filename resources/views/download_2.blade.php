<!DOCTYPE html>
<html>


<meta name="viewport" content="width=SITE_MIN_WIDTH, initial-scale=1, maximum-scale=1">

<style>

page[size="A4"] {  
  width: 21cm;
  height: 29.7cm; 
}

body {
  background: rgb(204,204,204); 
}
page {
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
page[size="A4"] {  
  width: 21cm;
  height: 29.7cm; 
}

@media print {
  body, page {
    margin: 0;
    box-shadow: 0;
  }
}
</style>


<body style="background-color: #f1f1f1; ">


    @php
    $sn = 0;
    @endphp

    @foreach($qr_strings as $qrcode)
    
    <div class="container my-3" style="background-color: white;  border : 5px solid gray; ">
        
        <div class="row" style="border-bottom: 5px solid grey; border-top: 5px solid grey;  ">
            <div class="my-3 col-md-8 text-center" style="border-top: 5px solid grey; border-bottom: 5px solid grey; 
			border-right: 5px solid grey; padding: 10px">

                <img class="img-fluid" src="{{$banner}}" width="600px" height="400px" style="padding:50px">

            </div>
            <div class="my-3 col-md-4 text-center"
                style="border-top: 5px solid grey; border-bottom: 5px solid grey;  padding: 10px">

                <!-- <img src="{{$banner}}" width="150px" height="150px"> -->


                <div class="my-3" style="padding: 10px">
                    <strong>E-Ticket</strong>
                    <div class="my-3">
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate($qrcode->qr_string)) !!} ">
                    </div>

                    <div class="my-3"><strong> Ticket {{$sn+1}} of {{$kuantiti}} ticket </strong></div>

                </div>

            </div>
        </div>

        

        @php
        $sn++;
        @endphp
    </div>
    @endforeach
  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    
    </script>
    


    <script type="text/php"> 
    
    if (isset($pdf)) { 
     //Shows number center-bottom of A4 page with $x,$y values
        $x = 250;  //X-axis i.e. vertical position 
        $y = 820; //Y-axis horizontal position
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";  //format of display message
        $font =  $fontMetrics->get_font("helvetica", "bold");
        $size = 10;
        $color = array(255,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
    
    </script> 
    
    </body> 
    </html>