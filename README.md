# Vicoland - Voting System

* [Intro](#intro)
* [Setup](#setup)
* [Running app](#running-app)
  * [Domains](#domains)
* [Swagger](#swagger)

## Intro
Because I use [ADR](https://devot.team/blog/adr-the-alternative-to-mainstream-architectures) in my present firm
and wanted to demonstrate how to use it to you guys, I chose it for this project's architecture rather than the more traditional MVC.

I also added things that wasn't mentioned in a task, because i felt without it project won't be representable, like:
* Custom Authenticator as a way of authorization
  * to do so in request add header `Authorization` with value you put in `AUTH_TOKEN_VOTING_SYSTEM` which is placed in `.env` file
* Swagger for endpoint
* seeders for filling database with test values

### Small notice
Based on provided frontend pages for Voting System, I decided to expand given `project` table with new fields 
`rating`, `review` and next ones `communication_rating`, `quality_of_work_rating`, `value_for_money_rating` which are
optional because of `Skip & Send` button on the second page of provided frontend.

Reason why I put then in a same `project` table is that this is rather simple feedback system, but 
if this feedback system would be more 'fluid' in a future for example new feedback criteriums will be added etc., I would add new
table for storing result of evaluation, because in current `project` table I would need to do `ALTER TABLE` 
every time when questionnaire changed. That on a big table could be `expensive` and be a `blocker` operation.
But, doing that approach with this simple table could only bring potentionally some `n+1` problems.

And last, I made only one endpoint for given tasks because i assumed that rating `can't` be done
in a moment of `Project` creation, rather when some work on that is finished and client can review
everything. So, I put all 3 tasks in one endpoint `api/v1/project/{project_id}` where `rating` and `review` are required 
fields and `communication_rating`, `quality_of_work_rating`, `value_for_money_rating` optional (`skip & save`).

## Setup

* clone project
* run `composer install` to install necessary dependencies
* copy `.env.example` to `.env`
* migrate database `php bin/console doctrine:migrations:migrate`
* run fixtures `php bin/console doctrine:fixtures:load`
  * this will fill database with some test values in Client, Vico and Project tables
* run tests with command `php bin/phpunit`

## Running app
To run locally start symfony server with command `symfony server:start`
### Domain

#### VotingSystem
- Action of Voting System, contain endpoint `PATCH /api/v1/project/{project_id}` that handle rating project

## Swagger
If server running, access `http://localhost:8000/api/doc` endpoint to see Swagger documentation

### Adding new endpoint
1. define new endpoint with Symfony attributes
* example:
```
    #[Route('/project/{project_id}', name: 'vs_api_v1_update_project', methods: ['PATCH'])]
    public function updateProject() 
    {
        # method implementation
        ...
    }
```
2. add documentation for Swagger
* go to the correct yaml file - `voting_system_api.yaml`
* add new endpoint under the key _paths_
```yaml
        paths:
        /api/v1/project/{project_id}:
          patch:
            tags:
              - Project
            summary: Rate project
            description: Client can rate, review and put additional ratings which are optional to project
            parameters:
              - name: project_id
                in: path
                description: Id of project whose values we want to update.
                required: true
                schema:
                  type: string
            requestBody:
              description: Update project.
              required: true
              content:
                application/json:
                  schema:
                    type: object
                    properties:
                      rating:
                        description: The field used to update project rating (range 1 to 5).
                        type: integer
                        example: 5
                      review:
                        description: The field used to update project review.
                        type: string
                        example: Test text that will be added as review
                      communication_rating:
                        description: The field used to update project communication rating (range 1 to 5).
                        type: integer
                        example: 3
                      quality_of_work_rating:
                        description: The field used to update project quality of work rating (range 1 to 5).
                        type: integer
                        example: 4
                      value_for_money_rating:
                        description: The field used to update project value for money rating (range 1 to 5).
                        type: integer
                        example: 5

            responses:
              '200':
                description: Returns updated project
                content:
                  application/json:
                    schema:
                      $ref: '#/components/schemas/ProjectRepresentation'
              '400':
                description: Bad request i.e. when some field is not provided in request
                content:
                  application/json:
                    schema:
                      $ref: '#/components/schemas/BadRequestError'
              '404':
                description: Not Found i.e. when project is not found.
                content:
                  application/json:
                    schema:
                      $ref: '#/components/schemas/NotFoundError'
```

* more options for adding endpoints can be found on [OpenAPI Specification](https://swagger.io/specification/)
