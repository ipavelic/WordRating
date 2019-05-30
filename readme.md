### WordSearcher JSON API

JSON API calculates popularity of given word based on ratio between positive and total number of results. System implements interface for future extension request.

Implemented endpoint: GitHub issue

###Getting started

* git clone project from gitHub
* composer install
* create mysql database
* doctrine:migrations:migrate

start working

####Examples

**http://127.0.0.1:8000/score/?term=rubyonrails**

Result:
    {"term":"rubyonrails","score":2.86}
    
**http://127.0.0.1:8000/score/?term=php**

Result:
    {"term":"php","score":3.52}
    
**http://127.0.0.1:8000/score/?term=symfony**

Result:
    {"term":"symfony","score":4.35}
    
**http://127.0.0.1:8000/score/?term=barbie**

Result:
    {"message":"No results for word barbie"}
    
####Future extension

If new endpoint needed, add new service in service.yaml and create new searcher logic (current searcher logic in GitHubSeracher.php)
Table sources in database is added for future extension purpose.


