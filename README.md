
# 01 - One-to-One                                                         


Let's populate the database. There will be two tables: a table with films and a table with directors.
One director can only make one movie, and one movie can belong to
only one director.
    
    php artisan make:model Director -mf   // -mf migration + factory
   
    php artisan make:model Film -mf
    
    
    In file `xxxx_xx_xx_xxxxxx_create_directors_table.php`
    
    public function up(): void
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }
    
    
    In file `xxxx_xx_xx_xxxxxx_create_filmss_table.php`
    
    public function up(): void
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('director_id')->constrained()->cascadeOnDelete();;
            $table->timestamps();
        });
    }
    
    The name of the `director_id` field is formed according to the rule: the name of the table with which we are connecting,
         underscore, and the column with which we associate in this table.
        
    
    Run migrations:     php artisan migrate
    
    Fill tables:
    
        In file database/factories/DirectorFactory.php in method `definition`:
        
            public function definition(): array
            {
                return [
                    'name' => $this->faker->name
                ];
            }
            
        In file `database/seeders/DatabaseSeeder.php`
        
            public function run(): void
            {
                Director::factory(30)->create();
            }
            
        php artisan db:seed     // This will create 30 directors in DB
        
        
        In file `database/factories/FilmFactory.php`
        
            public function definition(): array
            {
                return [
                    'name' => $this->faker->sentence(3),
                    'director_id' => $this->faker->unique()->numberBetween(1, 30)
                ];
            }
    
    
        In file `database/seeders/DatabaseSeeder.php`
        
            public function run(): void
            {
                // Director::factory(30)->create();
                Film::factory(30)->create();
            }
            
        
        php artisan db:seed
        
    ------------------------------------------------------------------------
    
    
    In file app/Models/Director.php create a relationship:
    
        public function film()
        {
            return $this->hasOne(Film::class);
        }
        
          
    In file routes/web.php display on page:
    
        Route::get('/', function () {
            $directors = Director::all();
            // $films = Film::all();

            foreach ($directors as $director) {
                echo "Director name: " . $director['name'] . ' --> ';
                echo "Film: " . $director->film['name'] . '<br>';
            }
        });
        
        
    Reverse relationship (film belongs to the director):
    
        In file app/Models/Film.php
        
            public function director()
            {
                return $this->belongsTo(Director::class); 
            }

            // as the second parameter, you can pass the name of the field by which the connection is made (if the fields are not named according to the rules, and Laravel did not understand what to look for)
                                                             
                                                             
        In file routes/web.php :
        
            Route::get('/', function () {
                //$directors = Director::all();
                 $films = Film::all();

                // foreach ($directors as $director) {
                //     echo "Director name: " . $director['name'] . ' --> ';
                //     echo "Film: " . $director->film['name'] . '<br>';
                //     echo "--------------------------------------<br>";
                // }

                foreach ($films as $film) {
                    echo "Film name: " . $film['name'] . ' --> ';
                    echo "Director: " . $film->director['name'] . '<br>';
                    echo "--------------------------------------<br>";
                }
            });  
            

# 02 - One-to-Many

    Now consider Authors and Books
    
        php artisan make:model Author -mf

        php artisan make:model Book -mf
        
        
    In file `database/migrations/xxx_xx_xx_xxxxxx_create_authors_table.php`
    
        public function up(): void
        {
            Schema::create('authors', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }
        
    
    In file `database/migrations/xxxx_xx_xx_xxxxxx_create_books_table.php`
    
        public function up(): void
        {
            Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->foreignId('author_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }   
        
    php artisan migrate
    
    
    Fill tables:
    
        In file database/factories/AuthorFactory.php в методе `definition`:
        
            public function definition(): array
            {
                return [
                    'name' => $this->faker->name
                ];
            }
            
        In file `database/seeders/DatabaseSeeder.php`
        
            public function run(): void
            {
                Author::factory(5)->create();
            }
            
        php artisan db:seed     // This will create 5 authors in DB
        
        
        In file `database/factories/BookFactory.php`
        
            public function definition(): array
            {
                return [
                    'title' => $this->faker->sentence(),
                    'author_id' => $this->faker->numberBetween(1, 5)
                ];
            }
    
    
        In file `database/seeders/DatabaseSeeder.php`
        
            public function run(): void
            {
                // Author::factory(5)->create();
                Book::factory(50)->create();
            }
            
        
        php artisan db:seed  // Create 50 books in DB
    
    
    Linking tables ONE TO MANY in models:
    
        
        we create the `books` method in the plural, because the relationship is ONE TO MANY and an author can have many books.
        
            public function books() // 
            {
                return $this->hasMany(Book::class);
            }
            
   
    In file routes/web.php display on page
    
        Route::get('/', function () {
    
            $authors = Author::all();

            foreach ($authors as $author) {
                echo 'Author name: ' . $author->name . '<br>';
                echo 'Author\'s Books:' . '<br>';
                foreach ($author->books as $book) {
                    echo 'Book: ' . $book->title . '<br>';
                }
                echo "--------------------------------------<br>";
            }
        });
        
        
    -------------------------------------------------------------
    
    One to Many in reverse order, i.e. display all books and for each book - the author:
    
    
        In file app/Models/Book.php
        
            use Illuminate\Database\Eloquent\Relations\BelongsTo;

        
            public function author(): BelongsTo
            {
                return $this->belongsTo(Author::class);
            }

        
        In file `routes/web.php`
        
            Route::get('/', function () {
    
                $authors = Author::all();
                $books = Book::all();

                foreach ($books as $book) {
                    echo '<b>Book title:</b> ' . $book->title . '<br>';
                    echo '<b>Author:</b>' . $book->author->name . '<br>';
                    echo '---------------------------------<br>';
                }
            });
           
    
    IF THE FIELDS IN THE TABLES SUDDENLY ARE NAMED INCORRECTLY (NOT ACCORDING TO THE LARAVEL RULES), THEN YOU SHOULD PASS THEIR NAMES TO THE RELATION METHODS, FOR EXAMPLE LIKE THIS:
    
        public function author(): BelongsTo
        {
            return $this->belongsTo(Author::class, 'author_id', 'id');
        }
            
            

# 03 - Many-to-Many                                                     

    Let's create three tables:
    
        - cinemas
            - id
            - name
            - timestamps
            
        - movies
            - id
            - name
            - timestamps
            
        - cinema_movie
            - id
            - cinema_id
            - movie_id
            - timestamps
            
            
    Create models:
    
        php artisan make:model Cinema -mf
        
        php artisan make:model Mivie -mf
        
        
    In file database/migrations/xxxx_xx_xx_xxxxxx_create_cinemas_table.php
    
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
            
    In file database/migrations/xxxx_xx_xx_xxxxxx_create_movies_table.php
    
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
    php artisan make:migration create_cinema_movie_table --create=cinema_movie
    
    In file database/migrations/xxxx_xx_xx_xxxxxx_create_cinema_movie_table.php
    
        Schema::create('cinema_movie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained();
            $table->foreignId('movie_id')->constrained();
            $table->timestamps();
        });
        
    php artisan migrate
    
    
    
   Fill in the tables:
    
        In file database/factories/CinemaFactory.php
        
            public function definition(): array
            {
                return [
                    'name' => $this->faker->word()
                ];
            }
            
        In file database/factories/MovieFactory.php
        
            public function definition(): array
            {
                return [
                    'name' => $this->faker->word()
                ];
            }
            
        In file database/seeders/DatabaseSeeder.php
        
            public function run(): void
            {
                // Director::factory(30)->create();
                // Film::factory(30)->create();

                // Author::factory(5)->create();
                // Book::factory(50)->create();

                Cinema::factory(8)->create();
                Movie::factory(30)->create();

            }
            
       Seed DB:
       
            php artisan db:seed

        
    Create an intermediate table:
      
        php artisan make:model CinemaMovie -f


        In file app/Models/CinemaMovie.php
         
            class CinemaMovie extends Model
            {
                use HasFactory;

                protected $table = 'cinema_movie';
            }

        In file database/factories/CinemaMovieFactory.php
        
            public function definition(): array
            {
                return [
                    'cinema_id' => $this->faker->numberBetween(1, 8),
                    'movie_id' => $this->faker->numberBetween(1, 30)
                ];
            }

        
        In file database/seeders/DatabaseSeeder.php
        
            public function run(): void
            {
            // Director::factory(30)->create();
            // Film::factory(30)->create();

            // Author::factory(5)->create();
            // Book::factory(50)->create();

            // Cinema::factory(8)->create();
            // Movie::factory(30)->create();
            
            CinemaMovie::factory(50)->create();
            }


        php artisan db:seed
        
        
    CREATE RELATIONSHIPS:
    
        In file app/Models/Cinema.php
        
            public function movies()
            {
                return $this->belongsToMany(Movie::class);
            }
        
        
    Let's output for each cinema its films:
    
        In file `routes/web.php`
        
            $cinemas = Cinema::all();

            foreach ($cinemas as $cinema) {
                echo '<b>Cinema: </b>' . $cinema->name . '<br>';
                echo '<b>Movies: </b><br>';
                foreach ($cinema->movies as $movie) {
                    echo 'Movie: ' . $movie->name . '<br>';
                }
                echo "-----------------------------------------<br>";
            }
            
            
    REVERSE RELATIONSHIP:
    
        In file app/Models/Movie.php
        
            public function cinemas()
            {
                return $this->belongsToMany(Cinema::class);
            }


        In file `routes/web.php`
        
            $movies = Movie::all();

            foreach ($movies as $movie) {
                echo '<b>Movie: </b>' . $movie->name . '<br>';
                echo '<b>Cinemas: </b><br>';
                foreach ($movie->cinemas as $cinema) {
                    echo 'Cinema: ' . $cinema->name . '<br>';
                }
                echo "-----------------------------------------<br>";
            }
            
            
            

# 04 - Has One Through and Has Many Through                                                  


    There are THREE tables: users, projects, tasks

    Each user can create a project (linked by user_id)
    
    Each task (task) belongs to some project
    
    Has Many Through
    
        This relationship means that, for example, when addressing a specific user, you can, BYPASSING the intermediate table (projects), 
        select, for example, all the tasks of this user from the tasks table
        
    
    CREATE RELATIONSHIPS:
    
        In model `User` (app/Models/User.php):
        
            
            public function tasks()
            {
                return $this->hasManyThrough(Task::class, Project::class);
            }

            // The first parameter is the destination table, i.e. Task Model,
            // and the second - through which table, i.e. Project model
            
            
    Display the result (we select all the tasks of the user through his projects):
    
        In file `routes/web.php`:
        
            use App\Models\User;
            ...
            
            Route::get('/', function () {
            
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
        
        
    Has One Through - almost the same, only a user can have only one task
    

# 05 - One To Many Polymorphic


    php artisan make:model Product -mf
    
        In file `database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php`
    
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
     
    php artisan make:model Photo -mf       
    
        In file `database/migrations/xxxx_xx_xx_xxxxxx_create_photos_table.php`
        
            Schema::create('photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('imageable_id');
                $table->string('imageable_type');
                $table->string('filename');
                $table->timestamps();
        });
        
        
    php artisan migrate
    
    In file `database/factories/ProductFactory.php`
    
        public function definition(): array
        {
            return [
                'name' => $this->faker->word()
            ];
        }
    
    In file `database/seeders/DatabaseSeeder.php` in method `run()`
        
        Product::factory(10)->create();
    
    php artisan db:seed  
    
    
    In file `database/factories/PhotoFactory.php`
    
        public function definition(): array
        {
            return [
                'imageable_id' =>$this->faker->numberBetween(1, 10),
                'imageable_type' => 'App\Models\Product',
                'filename' => $this->faker->word() . ".jpg"
            ];
        }    
        
    In file `database/seeders/DatabaseSeeder.php` in method `run()`
    
          Photo::factory(50)->create();
        
    php artisan db:seed
    
    In file `database/factories/PhotoFactory.php` add string 'App\Models\User'
    
        public function definition(): array
        {
            return [
                'imageable_id' =>$this->faker->numberBetween(1, 10),
                'imageable_type' => 'App\Models\User',
                'filename' => $this->faker->word() . ".jpg"
            ];
        }
    
    In file `database/seeders/DatabaseSeeder.php` in method `run()`
    
        Photo::factory(30)->create();
  
    php artisan db:seed 
            
    
    
    CREATE RELATIONSHIPS:
    
        In model `app/Models/Photo.php` define the `imageable` method. The name MUST match the `imageable_id` column name prefix 
        in the `photos` table
        
            public function imageable()
            {
                return $this->morphTo();
            }
         
        Further, depending on where the relationship will be used (for example, in User), we define the `photos` method
        
        DISPLAY A PHOTO OF A SPECIFIC USER
        
            In model `app/Models/User.php` 
            
                public function photos()
                {
                    return $this->morphMany(Photo::class, 'imageable');
                }
        
            // With this method, you can get all the images that belong to some user.
                
        In file `routes/web.php` in Route::get('/', function() {...
        
            $users = User::all();

            foreach ($users as $user) {
                echo '<b>User: </b>' . $user->name . '<br>';
                echo '<b>Photos: </b><br>';
                foreach ($user->photos as $photo) {
                    echo 'Photo: ' . $photo->filename . '<br>';
                }
                echo "-----------------------------------------<br>";
            }
        
        
        DISPLAY A PHOTO OF A SPECIFIC PRODUCT
        
            In file `app/Models/Product.php`
            
                public function photos()
                {
                    return $this->morphMany(Photo::class, 'imageable');
                }
        
        
        
        
        
        



