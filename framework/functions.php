<?php

function convertToDate($date)
{
	if($date == null)
		return null;

	$months = array(
		'janvier'	=>'jan',
		'février'	=>'feb',
		'mars'		=>'march',
		'avril'		=>'apr',
		'mai'		=>'may',
		'juin'		=>'jun',
		'juillet'   =>'jul',
		'août'	    =>'aug',
		'septembre' =>'sep',
		'octobre'	=>'oct',
		'novembre'	=>'nov',
		'décembre'	=>'dec'
	);

	$date = strtr(strtolower($date), $months);
	return date("d-m-Y", strtotime($date));
}

function showData($data)
{
	if($data == null):

		print '<i>information inconnue</i>';

	elseif(gettype($data) == "array"):

		print implode("<span>", $data).'</span>';

	elseif(gettype($data) == "object"):

		$labels_sections = array(
			'actors_original_dubbing' => "Doubleurs (voix originales)",
			'actors_french_dubbing'   => "Doubleurs (voix françaises)",
			'actors' 				  => "Acteurs",
		);

		foreach ($data as $section => $actors):

			if($labels_sections[$section] != "")
				print '<h2>'.$labels_sections[$section].'</h2>';
			
			$i = 1;
			foreach ($actors as $actor):
				
				print '<div class="actor">';
				print '<span class="number">' . $i . '</span>';
				print '<span class="name" clicktocopy="true">' . $actor->name . '</span>';

				if($actor->role == "" || ucfirst($actor->role) == "Actrice" || ucfirst($actor->role) == "Acteur"):				
					print '<span class="role"><i>rôle inconnu</i></span>';
				else:
					print '<span class="role" clicktocopy="true">' . $actor->role . '</span>';
				endif;

				print '</div>';
				$i++;

			endforeach;



		endforeach;

	else:

		print $data;

	endif;
}

?>