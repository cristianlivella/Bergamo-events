		function fillCards(array){
			
			var cards = "";
			for(var i=0; i<5;i++){
				var title = "Download";
				var description = "Festival della innovazione, blablablablablablablablablbalablabla";
				var address = "Parco della Preistoria, Viale Ponte Vecchio, Rivolta d' Adda, CR";
				
				var iframe = '<iframe class="card-img-top" frameborder="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=it&geocode=&q='
				iframe +=  address.replace(",", "").replace(" ", "+") + '&z=14&output=embed"></iframe>'
				
				var card = "";
				card += '<div class=".col-xl-3 col-lg-4 col-md-6 col-sm-12 text-center" style="margin-bottom:10px">';
				card += '	<div class="card center-block" style="width: 18rem;">';
				card += iframe;
				card += '		<div class="card-body">';
				card += '			<h5 class="card-title">' + title + '</h5>';
				card += '			<p class="card-text">' + description + '</p>';
				//card += '			<a href="#" class="btn btn-outline-light" style="background-color:#8a0303">Go somewhere</a>';
				card += '		</div>';
				card += '	</div>';
				card += '</div>';
				
				cards += card;
			}
			document.getElementById("cards").innerHTML = cards;
		}