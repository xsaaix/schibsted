Since I didn't see a point in reinventing the wheel and writing my own GitHub API Service, I decided to use the existing Laravel Package.
Normally for something this small I'd also go with Lumen but I haven't used Laravel in a while and wanted to brush up on it a bit. Hopefully it's not an issue.

The tests are just simple tests to check basic usage; unfortunately I ran out of time and had to focus on other things so in ideal world there would be more of those, for instance testing the service itself and what it returns.

The application has 3 endpoints
 - /api/repository/compare/name/{$name1}/{$name2} - @compareRepositoriesByNames- search repos by names
 - /api/repository/compare/user/{$user1}/{$user2}/name/{$name1}/{$name2} @compareRepositoriesByUsersAndNames - get repos by explicit username/repo combination
 - /api/repository/compare/url?url1=&url2= @compareRepositoriesByURLs - get repos by explicit username/repo combination

As the task mentioned searching repositories by names and not names and users, I decided to use the search functionality of the GitHub API but thought that maybe a username/name would be nice as well.
URL one just splits the string to get what it needs.

Endpoints return statistics of both repositories, a comparison of values and if you uncomment the business logic - keys of the winner of the comparison.

Extra functionality includes the service being able to compare more than 2 repositories.

Installation process
 - clone the repo
 - run ```composer install```
 - create .env on the basis of .env.example (copy paste)
 - run ```php artisan key:generate```
 - run ```php artisan vendor:publish``` and select the one for GitHub (should be 3)
 - change GITHUB_DEFAULT_CONNECTION in the .env if you wish. It's set to none by default and should be sufficient for testing. You then should also adjust ```config/github.php``` accordingly
 - run ```php artisan serve``` to start the server

 Tests run by ```phpunit``` command
