<?php
session_start();
include 'config.php';
// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$movies = [];
$search_query = "";
//THIS IS THE EXAMPLE OF HOW DATA DATA OBJECT WHICH IS FETCHED FROM OMDB api
/* {"Title":"The Lord of the Rings: The Fellowship of the Ring","Year":"2001","Rated":"PG-13",
"Released":"19 Dec 2001","Runtime":"178 min","Genre":"Adventure, Drama, Fantasy","Director":"Peter Jackson",
"Writer":"J.R.R. Tolkien, Fran Walsh, Philippa Boyens",
"Actors":"Elijah Wood, Ian McKellen, Orlando Bloom",
"Plot":"A meek Hobbit from the Shire and eight companions set out on a journey to destroy the powerful One Ring and save Middle-earth from the Dark Lord Sauron.",
"Language":"English, Sindarin","Country":"New Zealand, United States, United Kingdom","Awards":"Won 4 Oscars. 125 wins & 126 nominations total",
"Poster":"https://m.media-amazon.com/images/M/MV5BNzIxMDQ2YTctNDY4MC00ZTRhLTk4ODQtMTVlOWY4NTdiYmMwXkEyXkFqcGc@._V1_SX300.jpg",
// "Ratings":[{"Source":"Internet Movie Database","Value":"8.9/10"},{"Source":"Rotten Tomatoes","Value":"92%"},
// {"Source":"Metacritic","Value":"92/100"}],"Metascore":"92","imdbRating":"8.9","imdbVotes":"2,145,663","imdbID":"tt0120737",
// "Type":"movie","DVD":"N/A","BoxOffice":"$319,372,078","Production":"N/A","Website":"N/A","Response":"True"}
// */

// Handle movie search
//Checks if a search query (q) has been submitted via URL
//urlencode() ensures the search text is safely formatted for URL

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_query = urlencode($_GET['q']);
    $apiUrl = "http://www.omdbapi.com/?s={$search_query}&apikey={$OMDB_API_KEY}";
    $response = file_get_contents($apiUrl);
    //Converts JSON response into a PHP associative array
    $data = json_decode($response, true);
    //The API returns movie search results inside ['Search']
    //Store them into $movies array if they exist  
    if (isset($data['Search'])) {
        $movies = $data['Search'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movie Search (OMDb)</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 
    <a href="logout.php">Logout</a>
</h2>

<form method="GET" action="">
    <input type="text" name="q" placeholder="Search for a movie..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($search_query && empty($movies)): ?>
    <p>No results found for "<?php echo htmlspecialchars($_GET['q']); ?>"</p>
<?php endif; ?>

<div class="movies">
    <!--- loop thorugh the movie array -->  
<?php foreach ($movies as $m): ?>
    <div class="movie-card">
        <!--- for each result show its poster attribute i.e the image, its title,rating ,year --> 
        <img src="<?php echo $m['Poster'] != 'N/A' ? $m['Poster'] : 'https://via.placeholder.com/200x300?text=No+Image'; ?>" alt="Poster">
        <h3><?php echo htmlspecialchars($m['Title']); ?> (<?php echo $m['Year']; ?>)</h3>
        <!--- redirect to details page -->
        <a href="movie_details.php?imdb=<?php echo $m['imdbID']; ?>">View Details</a>
    </div>
<?php endforeach; ?>
</div>
</body>
</html>
