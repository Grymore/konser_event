<html>

<body>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <meta name="viewport" content="width=SITE_MIN_WIDTH, initial-scale=1, maximum-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

  <style>
    body {
      background: rgb(204, 204, 204);
    }

    page[size="A4"] {
      background: white;
      width: 21cm;
      height: 29.7cm;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
      box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
    }

    @media print {

      body,
      page[size="A4"] {
        margin: 0;
        box-shadow: 0;
      }
    }
  </style>

  @php
  $sn = 0;
  @endphp

  @foreach($qr_strings as $qrcode)

  <div class="row">
  <div class="col-md-6"><img src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate($qrcode->qr_string)) !!} "></div>
  <div class="col-md-6"><img src="data:image/png;base64, {!! base64_encode(QrCode::size(150)->generate($qrcode->qr_string)) !!} "></div>
</div>



  @php
  $sn++;
  @endphp

  @endforeach


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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">


    </script>

</body>

</html>