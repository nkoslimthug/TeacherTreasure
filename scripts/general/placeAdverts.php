<?php
	if(isset($left)){
		echo"
			<div id='cf' >
				<img class='bottom' src='".$root_path."images/adverts/advert1.png' width='100%' height='100%' />
				<img class='top' src='".$root_path."images/adverts/advert2.png' width='100%' height='100%'/>
			</div>
		";
	}	
	if(isset($right)){
		echo"
			<div id='cf' >
				<img class='bottom' src='".$root_path."images/adverts/advert1.png' height='350%' width='120%' />
				<img class='top' src='".$root_path."images/adverts/advert2.png' height='350%' width='120%'/>
			</div>
		";
	}
?>