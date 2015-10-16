<?php 

class IMDb 
{
	private $JSON;

	private $page_home;
	private $page_casting;
	private $page_details;

	private $id;

	private $titles;
	private $release_dates;
	
	public function loadPage($id)
	{
		if(!isset($id))
			exit();

		$this->page_home    = file_get_html('http://www.imdb.com/title/' .$id . '/');
		$this->page_casting = file_get_html('http://www.imdb.com/title/' .$id . '/fullcredits');
		$this->page_details = file_get_html('http://www.imdb.com/title/' .$id . '/releaseinfo');

		$this->id = $id;

		// TITRES
		$this->titles['french'] = $this->page_home->find('h1.header span.itemprop', 0)->plaintext;

		// DATES DE SORTIES
		// $this->release_dates['france'] = 
		$this->loadDates();

		// print $this->titles;

	}

	public function getJSON()
	{
		$this->JSON['id'] 		 	 	 = $this->id;
		$this->JSON['titles'] 			 = $this->titles;
		$this->JSON['release_dates'] 	 = $this->release_dates;

		return json_encode($this->JSON);
	}

	private function loadDates()
	{
		$nodes = $this->page_details->find('#release_dates tr');

		foreach ($nodes as $elements):

			if($elements->find('td', 0)->plaintext == "France")
				$this->release_dates['france'] = convertToDate($elements->find('.release_date', 0)->plaintext);

		endforeach;
	}

}

?>