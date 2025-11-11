<?php
session_start();
include 'config.php';   
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['imdb'])) {
    header("Location: movies.php");
    exit;
}
//get the contents via the imdb id
$imdb_id = $_GET['imdb'];
$apiUrl = "http://www.omdbapi.com/?i={$imdb_id}&plot=full&apikey={$OMDB_API_KEY}";
$response = file_get_contents($apiUrl);
$movie = json_decode($response, true);
?>
<!DOCTYPE html>
<html>
<head>
<!-- htmlspecialchar is used to safely display text that might contain HTML characters, preventing broken layout and protecting against XSS attacks-->
    <title><?php echo htmlspecialchars($movie['Title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2><?php echo htmlspecialchars($movie['Title']); ?> (<?php echo $movie['Year']; ?>)</h2>
<a href="movies.php">⬅ Back to search</a> | <a href="logout.php">Logout</a>

<!--display all the different details about the movie -->
<div class="movie-detail">
    <img src="<?php echo $movie['Poster'] != 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/200x300?text=No+Image'; ?>" alt="Poster">
    <div>
        <p><strong>Genre:</strong> <?php echo $movie['Genre']; ?></p>
        <p><strong>Director:</strong> <?php echo $movie['Director']; ?></p>
        <p><strong>Actors:</strong> <?php echo $movie['Actors']; ?></p>
        <p><strong>Plot:</strong> <?php echo $movie['Plot']; ?></p>
        <p><strong>IMDB Rating:</strong> <?php echo $movie['imdbRating']; ?></p>
        <p><strong>Runtime:</strong> <?php echo $movie['Runtime']; ?></p>
    </div>
</div>
</body>
</html>
