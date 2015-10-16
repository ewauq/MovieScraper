<?php 

class Allocine 
{
	private $JSON;

	private $page_home;
	private $page_casting;

	private $id;

	private $details;
	private $titles;
	private $release_dates;
	private $genres;
	private $countries;
	private $distributor;
	private $synopsis;
	private $directors;
	private $actors;
	private $writers;
	private $producers;

	private $production_year;
	private $boxoffice_france;
	private $budget;
	private $languages;
	private $color;
	private $production_format;
	private $movie_type;
	private $audio_format;
	private $projection_format;
	private $visa;	
	
	public function loadPage($id)
	{
		if(!isset($id))
			exit();

		$this->page_home    = file_get_html('http://www.allocine.fr/film/fichefilm_gen_cfilm=' . $id . '.html');
		$this->page_casting = file_get_html('http://www.allocine.fr/film/fichefilm-' .$id . '/casting/');

		$this->id = $id;

		// Chargement des détails (en prio)
		$this->loadDetails();

		// TITRES
		$this->titles['french']   = trim($this->page_home->find('.tt_r26', 0)->plaintext);
		$this->titles['original'] = @$this->details['original_title'];

		// DATES
		$this->release_dates['france']  = convertToDate($this->page_home->find('span[itemprop="datePublished"]', 0)->plaintext);
		$this->release_dates['bluray']  = convertToDate($this->details['release_date_bluray']);
		$this->release_dates['dvd']     = convertToDate($this->details['release_date_dvd']);
		$this->release_dates['vod']     = convertToDate($this->details['release_date_vod']);
		$this->release_dates['reprise'] = convertToDate($this->details['release_date_reprise']);

		// AUTRES DETAILS
		$this->production_year   = @$this->details['production_year'];
		$this->boxoffice_france  = @$this->details['boxoffice_france'];
		$this->budget 			 = @$this->details['budget'];
		$this->languages 		 = @$this->details['languages'];
		$this->color 			 = @$this->details['color'];
		$this->production_format = @$this->details['production_format'];
		$this->movie_type 		 = @$this->details['movie_type'];
		$this->audio_format 	 = @$this->details['audio_format'];
		$this->projection_format = @$this->details['projection_format'];
		$this->visa 			 = @$this->details['visa'];

		// GENRES
		$this->loadGenres();

		// PAYS
		$this->loadCountries();

		// DISTRIBUTOR
		$this->distributor = $this->details['distributor'];

		// SYNOPSIS
		$this->synopsis = strip_tags(trim($this->page_home->find('p[itemprop="description"]', 0)->parent()->plaintext));

		// REALISATEURS
		$this->loadDirectors();

		// ACTEURS
		$this->loadActors();

		// SCENARISTES
		$this->loadWriters();

		// PRODUCTEURS
		$this->loadProducers();
	}

	public function getJSON()
	{
		$this->JSON['id'] 		 	 	 = $this->id;
		$this->JSON['titles'] 		 	 = $this->titles;
		$this->JSON['release_dates'] 	 = $this->release_dates;
		$this->JSON['genres'] 		 	 = $this->genres;
		$this->JSON['countries'] 	 	 = $this->countries;
		$this->JSON['distributor'] 	 	 = $this->distributor;
		$this->JSON['synopsis'] 	 	 = $this->synopsis;
		$this->JSON['directors']  	 	 = $this->directors;
		$this->JSON['actors'] 	 	 	 = $this->actors;
		$this->JSON['writers'] 	 	 	 = $this->writers;
		$this->JSON['producers'] 	  	 = $this->producers;
		$this->JSON['audio_format'] 	 = $this->audio_format;
		$this->JSON['boxoffice_france']  = $this->boxoffice_france;
		$this->JSON['budget'] 	 	     = $this->budget;
		$this->JSON['color'] 	 	 	 = $this->color;
		$this->JSON['languages'] 	 	 = $this->languages;
		$this->JSON['movie_type'] 	 	 = $this->movie_type;
		$this->JSON['production_format'] = $this->production_format;
		$this->JSON['production_year'] 	 = $this->production_year;
		$this->JSON['projection_format'] = $this->projection_format;
		$this->JSON['visa'] 	 	 	 = $this->visa;

		return json_encode($this->JSON);
	}

	private function loadDetails()
	{
		$lines = $this->page_home->find('div.expendTable .fs11 tr');

		$param_names = array(
			'Récompenses'			 => 'awards',
			'Titre original' 		 => 'original_title',
			'Distributeur' 			 => 'distributor',
			'Secrets de tournage' 	 => 'trivia',
			'Année de production'	 => 'production_year',
			'Box Office France'		 => 'boxoffice_france',
			'Date de sortie VOD'	 => 'release_date_vod',
			'Budget'				 => 'budget',
			'Date de sortie DVD'	 => 'release_date_dvd',
			'Date de reprise'		 => 'release_date_reprise',
			'Date de sortie Blu-ray' => 'release_date_bluray',
			'Langue'				 => 'languages',
			'Couleur'				 => 'color',
			'Format de production'   => 'production_format',
			'Type de film'			 => 'movie_type',
			'Format audio'			 => 'audio_format',
			'Format de projection'	 => 'projection_format',
			'N° de Visa'			 => 'visa'
		);

		foreach ($lines as $line):

			if($line->childNodes(0)->plaintext != ""):
				$value = trim($line->childNodes(1)->plaintext);
				if($value == "-") $value = null;
				$this->details[$param_names[$line->childNodes(0)->plaintext]] = $value;
			endif;

			if($line->childNodes(3)->plaintext != ""):
				$value = trim($line->childNodes(4)->plaintext);
				if($value == "-") $value = null;				
				$this->details[$param_names[$line->childNodes(3)->plaintext]] = $value;
			endif;

		endforeach;

		ksort($this->details);
	}

	private function loadGenres()
	{
		$genres = $this->page_home->find('.data_box_table span[itemprop="genre"]');

		foreach ($genres as $genre):
			$this->genres[] = trim($genre->plaintext);
		endforeach;
	}

	private function loadCountries()
	{
		$nodes = $this->page_home->find('.data_box_table tr');
		
		foreach ($nodes as $elements):

			if(trim($elements->find('th', 0)->plaintext) == "Nationalité"):

				$countries = explode(" , ", trim($elements->find('td', 0)->plaintext));

				foreach ($countries as $country):
					$this->countries[] = ucfirst(trim($country));
				endforeach;

			endif;
				 
		endforeach;
	}

	private function loadDirectors()
	{
		//Récupération de la première partie (avec les portraits)
		$nodes = $this->page_casting->find('li[itemprop="director"] span[itemprop="name"]');

		$directors = array();

		foreach ($nodes as $director):
			$directors[] = trim($director->plaintext);
		endforeach;

		//Récupération de la seconde partie (sans les portraits)
		$start = strpos($this->page_casting, '<h2 class="tt_r22 d_inline_block"> Réalisateurs </h2>');
		$end   = strpos($this->page_casting, '<!-- sep -->');

		$block = str_get_html(substr($this->page_casting, $start, ($end-$start)));

		$nodes = $block->find('.table_02 tr span');

		foreach ($nodes as $director):
			$directors[] = trim($director->plaintext);
		endforeach;

		$this->directors = $directors;
	}

	private function loadActors()
	{
		$start = strpos($this->page_casting, '<div id="direction">');
		$end   = strpos($this->page_casting, '<!--/col_main-->');
		$block = str_get_html(substr($this->page_casting, $start, ($end-$start)));

		$actors_sections = array(
			array(
				'section_id'    => 'actors',
				'section_title' => 'Acteurs et actrices',
				'section_used'  => (strpos($block, 'Acteurs et actrices') > 0)? true : false
			),
			array(
				'section_id'    => 'actors_original_dubbing',
				'section_title' => 'Acteurs de doublage (Voix originales)',
				'section_used'  => (strpos($block, 'Acteurs de doublage (Voix originales)') > 0)? true : false
			),
			array(
				'section_id'    => 'actors_french_dubbing',
				'section_title' => 'Acteurs de doublage (Voix locales)',
				'section_used'  => (strpos($block, 'Acteurs de doublage (Voix locales)') > 0)? true : false
			)
		);

		$block  = array();
		$exclude_roles = array("actrice", "acteur", "", false, null); // Rôles à effacer par défaut (null)

		foreach ($actors_sections as $section => $data):

			if($data['section_used']):

				$start = strpos($this->page_casting, $data['section_title']);
				$end   = strpos($this->page_casting, '<!--/col_main-->');

				$block[$data['section_id']] = str_get_html(substr($this->page_casting, $start, ($end-$start)));
				$block[$data['section_id']] = str_get_html(substr($block[$data['section_id']], 0, strpos($block[$data['section_id']], '<!-- sep -->')));

				// print $block[$data['section_id']];

				// Partie avec les portraits
				$nodes = $block[$data['section_id']]->find('.media_list_02 li');

				$i = 0;
				foreach ($nodes as $actor):

					$actor_id = null;
					preg_match('/cpersonne=([0-9]*+).html/', $actor->find('a', 0)->href, $actor_id);

					$this->actors[$data['section_id']][$i]['id']   = $actor_id[1];
					$this->actors[$data['section_id']][$i]['name'] = trim($actor->find('img', 0)->alt);
					$this->actors[$data['section_id']][$i]['role'] = @trim(str_replace("Rôle : ", "", $actor->find('p', 1)->plaintext));

					if(!in_array(strtolower(@$actor->find('p', 1)->plaintext), $exclude_roles)):
						$this->actors[$data['section_id']][$i]['role'] = @trim(str_replace("Rôle : ", "", $actor->find('p', 1)->plaintext));
					else:
						$this->actors[$data['section_id']][$i]['role'] = null;
					endif;

					$i++;
				endforeach;

				// Partie sans les portraits
				$nodes = $block[$data['section_id']]->find('.table_02 tr');
				 
				$i = count($this->actors[$data['section_id']]);
				foreach ($nodes as $actor):

					$actor_id = null;
					$returnValue = preg_match('/cpersonne=([0-9]*+).html/', @$actor->find('a', 0)->href, $actor_id);

					$this->actors[$data['section_id']][$i]['id']   = @$actor_id[1];
					$this->actors[$data['section_id']][$i]['name'] = trim($actor->find('.tab_tooltip span', 0)->plaintext);

					if(!in_array(strtolower(@$actor->find('td', 0)->plaintext), $exclude_roles)):
						$this->actors[$data['section_id']][$i]['role'] = @$actor->find('td', 0)->plaintext;
					else:
						$this->actors[$data['section_id']][$i]['role'] = null;
					endif;

					$i++;
				endforeach;
				
			endif;

		endforeach;
	}

	private function loadWriters()
	{
		$start = strpos($this->page_casting, '<h2 class="tt_r22 d_inline_block"> Scénario </h2>');
		$end   = strpos($this->page_casting, '<!--/col_main-->');

		$block = str_get_html(substr($this->page_casting, $start, ($end-$start)));
		$block = str_get_html(substr($block, 0, strpos($block, '<!-- sep -->')));

		$nodes = $block->find('.table_02 tr');

		$i = 0;
		foreach ($nodes as $writer):

			if($writer->find('td', 0)->plaintext != "Scénariste" && $writer->find('td', 0)->plaintext != "Co-scénariste")
				break;
			
			$this->writers[$i] = trim($writer->find('span', 0)->plaintext);
			$i++;

		endforeach;
	}	

	private function loadProducers()
	{
		$start = strpos($this->page_casting, '<h2 class="tt_r22 d_inline_block"> Production </h2>');
		$end   = strpos($this->page_casting, '<!--/col_main-->');

		$block = str_get_html(substr($this->page_casting, $start, ($end-$start)));
		$block = str_get_html(substr($block, 0, strpos($block, '<!-- sep -->')));

		$nodes = $block->find('.table_02 tr');

		$i = 0;
		foreach ($nodes as $producer):

			if($producer->find('td', 0)->plaintext != "Producteur" && $producer->find('td', 0)->plaintext != "Productrice")
				break;
			
			$this->producers[$i] = trim($producer->find('span', 0)->plaintext);
			$i++;

		endforeach;
	}
}

?>