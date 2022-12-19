<html>
<body>
	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="{{ asset('css/tiket.css') }}">
<meta name="viewport" content="width=SITE_MIN_WIDTH, initial-scale=1, maximum-scale=1">



@php
$sn = 0;
@endphp

@foreach($qr_strings as $qrcode)


<div class="ticket">
	<div class="left">
		<div class="image">
			<p class="admit-one">
				<span>KONSER 2023</span>
				<span>KONSER 2023</span>
				<span>KONSER 2023</span>
			</p>
			<div class="ticket-number">
				<p>
					#{{$invoice_tiket}}
				</p>
			</div>
		</div>
		<div class="ticket-info">
			<p class="date">
				<span>SATURDAY</span>
				<span class="june-29">FEBRUARY 09TH</span>
				<span>2023</span>
			</p>
			<div class="show-name">
				<!-- <h1>SOUR Prom</h1> -->
				<h2>Denny CakNan</h2>
			</div>
			<div class="time">
				<p>8:00 PM <span>TO</span> 11:00 PM</p>
				<p>OPEN GATE <span>@</span> 7:00 PM</p>
			</div>
			<p class="location"><span>Stadion Madiun</span>
				<span class="separator"><i class="fas fa-volume-up"></i></span><span>Madiun, Jawa Tengah</span>
			</p>
		</div>
	</div>
	<div class="right">
		<p class="admit-one">
			<span>KONSER 2023</span>
			<span>KONSER 2023</span>
			<span>KONSER 2023</span>
		</p>
		<div class="right-info-container">
			<div class="show-name">
				<!-- <h1>SOUR Prom</h1> -->
			</div>
			<div class="time">
				<p>8:00 PM <span>TO</span> 11:00 PM</p>
				<p>DOORS <span>@</span> 7:00 PM</p>
			</div>
			<div class="barcode">
				<!-- <img src="https://external-preview.redd.it/cg8k976AV52mDvDb5jDVJABPrSZ3tpi1aXhPjgcDTbw.png?auto=webp&s=1c205ba303c1fa0370b813ea83b9e1bddb7215eb" alt="QR code"> -->
				<div>{!! QrCode::size(100)->generate($qrcode->qr_string) !!}</div>
				<br>
			</div>
			<div>
				<p>{{$sn+1}} of {{$kuantiti}} ticket </p>
			</div>

		</div>
	</div>
</div>

@php
$sn++;
@endphp
</div>
@endforeach


</body>
</html>