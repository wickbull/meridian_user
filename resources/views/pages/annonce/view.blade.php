<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Annonce</title>
</head>
<body>
<a href="/">go back</a>
	<center>{{ $annonce->title }}</center>
	<center>{{ $annonce->body }}</center>
	{{ dump($annonce->getImage()) }}
	<center><img class="info" src="{{ $annonce->getImage() }}" alt=""></center>
	<center></center>
	<center></center>
	<center></center>
	<center></center>
   	
</body>
</html>