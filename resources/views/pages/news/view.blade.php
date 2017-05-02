<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Annonce</title>
</head>
<body>
<a href="/">go back</a>
	<center> title: {{ $news->title }}</center>
	<center> body: {{ $news->body }}</center>
	{{ dump($news->getImage()) }}
	<center><img class="info" src="{{ $news->getImage() }}" alt=""></center>
	<center></center>
	<center></center>
	<center></center>
	<center></center>
   	
</body>
</html>