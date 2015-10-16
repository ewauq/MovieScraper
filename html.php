<!doctype html>
<html lang="fr">
	<head>	
		<link rel="stylesheet" href="css/style.css" />
		<link rel="icon" type="image/png" href="css/logo.png">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src="js/scripts.js"></script>
		<script src="js/ZeroClipboard.js"></script>
		<title>MovieScrap</title>
	</head>
	<body>

		<div class="content">

			<h1>ID</h1>
			<article>
				<label>Identifiant</label>
				<span clicktocopy="true" class="value"><?php showData($json['allocine']->id); ?></span>
				<!-- <span clicktocopy="true" class="value"><?php showData($json['imdb']->id); ?></span> -->
			</article>

			<h1>Titres</h1>
			<article>
				<label>Titre FR</label>
				<span clicktocopy="true" class="value"><?php showData($json['allocine']->titles->french); ?></span>
				<!-- <span clicktocopy="true" class="value"><?php showData($json['imdb']->titles->french); ?></span> -->
			</article>

			<article>
				<label>Titre VO</label>
				<span clicktocopy="true" class="value"><?php showData($json['allocine']->titles->original); ?></span>
			</article>

			<h1>Dates de sorties</h1>
			<article>
				<label>Sortie France</label>
				<span class="value"><?php showData($json['allocine']->release_dates->france); ?></span>
				<!-- <span class="value"><?php showData($json['imdb']->release_dates->france); ?></span> -->
			</article>

			<article>
				<label>Reprise France</label>
				<span class="value"><?php showData($json['allocine']->release_dates->reprise); ?></span>
			</article>

			<article>
				<label>Sortie Blu-ray</label>
				<span class="value"><?php showData($json['allocine']->release_dates->bluray); ?></span>
			</article>

			<article>
				<label>Sortie DVD</label>
				<span class="value"><?php showData($json['allocine']->release_dates->dvd); ?></span>
			</article>

			<article>
				<label>Sortie VOD</label>
				<span class="value"><?php showData($json['allocine']->release_dates->vod); ?></span>
			</article>

			<h1>Informations générales</h1>
			<article>
				<label>Genres</label>
				<span class="value"><?php showData($json['allocine']->genres); ?></span>
			</article>

			<article>
				<label>Pays</label>
				<span class="value"><?php showData($json['allocine']->countries); ?></span>
			</article>

			<article>
				<label>Distributeur France</label>
				<span clicktocopy="true" class="value"><?php showData($json['allocine']->distributor); ?></span>
			</article>

			<article>
				<label>Synopsis</label>
				<span clicktocopy="true" class="value"><?php showData($json['allocine']->synopsis); ?></span>
			</article>

			<h1>Autres informations</h1>
			<article>
				<label>Année de production</label>
				<span class="value"><?php showData($json['allocine']->production_year); ?></span>
			</article>
			
			<article>
				<label>Box-office France</label>
				<span class="value"><?php showData($json['allocine']->boxoffice_france); ?></span>
			</article>
			
			<article>
				<label>Budget</label>
				<span class="value"><?php showData($json['allocine']->budget); ?></span>
			</article>
			
			<article>
				<label>Language</label>
				<span class="value"><?php showData($json['allocine']->languages); ?></span>
			</article>
			
			<article>
				<label>Couleur</label>
				<span class="value"><?php showData($json['allocine']->color); ?></span>
			</article>
			
			<article>
				<label>Format de production</label>
				<span class="value"><?php showData($json['allocine']->production_format); ?></span>
			</article>
			
			<article>
				<label>Type de film</label>
				<span class="value"><?php showData($json['allocine']->movie_type); ?></span>
			</article>
			
			<article>
				<label>Format audio</label>
				<span class="value"><?php showData($json['allocine']->audio_format); ?></span>
			</article>
			
			<article>
				<label>Format de projection</label>
				<span class="value"><?php showData($json['allocine']->projection_format); ?></span>
			</article>
			
			<article>
				<label>Visa #</label>
				<span class="value"><?php showData($json['allocine']->visa); ?></span>
			</article>

			<h1>Casting</h1>
			<article>
				<label>Réalisateurs</label>
				<span class="value"><?php showData($json['allocine']->directors); ?></span>
			</article>

			<article>
				<label>Acteurs</label>
				<span class="value"><?php showData($json['allocine']->actors); ?></span>
			</article>

			<article>
				<label>Scénaristes</label>
				<span class="value"><?php showData($json['allocine']->writers); ?></span>
			</article>

			<article>
				<label>Producteurs</label>
				<span class="value"><?php showData($json['allocine']->producers); ?></span>
			</article>

		</div>

	</body>
</html>