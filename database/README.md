# Database Overview

This [entity relationship diagram](https://mermaid.js.org/syntax/entityRelationshipDiagram.html) offers a high-level overview of key entities in the database. **This is meant to express design intention and is not a comprehensive breakdown. For full implementation detail, refer to the schema.**

```mermaid
erDiagram
    %% Player, team
    PLAYER ||..o{ POSITION : "in"
    POSITION ||--|| TEAM : "for"
    TEAM }o--|| CLUB : "represents"

    PLAYER ||..o{ TRANSFER : "underwent"
    TRANSFER ||..|{ TEAM : "from/to"

    PLAYER ||--|| COUNTRY : "is from"

    TEAM }o..o{ VENUE : "is home at"

    %% Fixture
    TEAM }o..o{ FIXTURE : "plays in"
    FIXTURE }o--|| TOURNAMENT : "in"

    FIXTURE }o..o{ ROUND : "in"
    FIXTURE }o..o{ SEASON : "in"

    FIXTURE_EVENT }o--|| FIXTURE : "occurs during"
    FIXTURE_EVENT ||--|| FIXTURE_EVENT_TYPE : "is of"

    %% Tournament
    TOURNAMENT }o--|| RULESET : "uses"
    RULESET ||--|{ RULE : "has"
    RULETYPE ||--|{ RULE : "uses"

    %% Standings
    STANDINGS }o..|| TEAM : ""
    STANDINGS }o..|| SEASON : ""
    STANDINGS }o..|| TOURNAMENT : ""

    %% Additional
    FIXTURE ||--|| VENUE : "in"
```

- While ROUNDS -> SEASONS -> TOURNAMENTS seems like a natural relational structure, in practice they are decoupled to avoid repetition and simplify querying

## Users and Clients

```mermaid
erDiagram
    %% Users/auth
    USER }o..o{ CLIENT : "works for"
    USER ||--o{ SESSION : "authenticates with"
    %% With CLIENT access, USER can administrate data owned by CLIENT
    %% CLIENT -> data relationships are omitted for visual clarity
    %% If USER is superuser, CLIENT ownership checks are ignored
```

- Standard Laravel authentication/permission entities are not visualised here
- USER is normally be related to a CLIENT, but there is also a superuser role which permits client-independent access. This is restricted to Framework staff
- If not superuser, assignment to a CLIENT grants USER access to all entities managed by CLIENT including tournaments, fixtures, players etc. according to the user's role
- Relationships between CLIENT and other entities are not visualised here for reasons of simplicity. If in doubt, check the table for a `client_id` field

## Sports

```mermaid
erDiagram
    %% Sports
    CLIENT }o--|| SPORT : ""
    TOURNAMENT }o--|| SPORT : ""
    RULESET }o--|| SPORT : ""
    CLUB }o--|| SPORT : ""
    TEAM }o--|| SPORT : ""
    PLAYER }o--|| SPORT : ""
    POSITION }o--|| SPORT : ""
    ROUND }o--|| SPORT : ""
```

- SPORT is a superuser-only entity managed by Framework
- All major entities are restricted to a single sport, including CLIENT. In the unlikely event that a given document ' crosses' sports, two separate documents must be created. For example, if player X plays both netball and basketball, then there will need to be one instance of player X for netball (within all the applicable data structures) and another one for basketball

## Statistics

```mermaid
erDiagram
    %% Statistics
    SEASON ||..|| STAT : ""
    FIXTURE ||..|| STAT : ""
    "(poly) PLAYER, TEAM..." ||..|| STAT : ""

    STAT ||--o{ STAT_RECORD : ""
```

- The STAT table is [polymorphic](https://laravel.com/docs/12.x/eloquent-relationships#polymorphic-relationships) so records can be associated with a specific PLAYER or TEAM, and potentially other entities in future
- FIXTURE and SEASON relationships to STAT are non-polymorphic and optional (nullable)
