<?php

use App\Models\Book;
use App\Models\Film;
use App\Models\User;
use App\Models\Movie;
use App\Models\Author;
use App\Models\Cinema;
use App\Models\Director;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //$directors = Director::all();
    //  $films = Film::all();

    // foreach ($directors as $director) {
    //     echo "Director name: " . $director['name'] . ' --> ';
    //     echo "Film: " . $director->film['name'] . '<br>';
    //     echo "--------------------------------------<br>";
    // }

    // foreach ($films as $film) {
    //     echo "Film name: " . $film['name'] . ' --> ';
    //     echo "Director: " . $film->director['name'] . '<br>';
    //     echo "--------------------------------------<br>";
    // }

    // $authors = Author::all();

    // foreach ($authors as $author) {
    //     echo '<b>Author name:</b> ' . $author->name . '<br>';
    //     echo '<b>Author\'s Books:</b>' . '<br>';
    //     foreach ($author->books as $number => $book) {
    //         echo $number + 1 . '. ' . 'Book: ' . $book->title . '<br>';
    //     }
    //     echo "--------------------------------------<br>";
    // }

    // $authors = Author::all();
    // $books = Book::all();

    // foreach ($books as $book) {
    //     echo '<b>Book title:</b> ' . $book->title . '<br>';
    //     echo '<b>Author:</b>' . $book->author->name . '<br>';
    //     echo '---------------------------------<br>';
    // }

    // $cinemas = Cinema::with('name');

    // foreach ($cinemas as $cinema) {
    //     echo '<b>Cinema: </b>' . $cinema->name . '<br>';
    //     echo '<b>Movies: </b><br>';
    //     foreach ($cinema->movies as $movie) {
    //         echo 'Movie: ' . $movie->name . '<br>';
    //     }
    //     echo "-----------------------------------------<br>";
    // }

    // $movies = Movie::all();

    // foreach ($movies as $movie) {
    //     echo '<b>Movie: </b>' . $movie->name . '<br>';
    //     echo '<b>Cinemas: </b><br>';
    //     foreach ($movie->cinemas as $cinema) {
    //         echo 'Cinema: ' . $cinema->name . '<br>';
    //     }
    //     echo "-----------------------------------------<br>";
    // }

    $users = User::all();

    foreach ($users as $user) {
        echo '<b>User: </b>' . $user->name . '<br>';
        echo '<b>Tasks: </b><br>';
        foreach ($user->tasks as $task) {
            echo 'Task: ' . $task->name . '<br>';
        }
        echo "-----------------------------------------<br>";
    }
});
