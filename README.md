# score-board
Football World Cup Score Board


For simplicity, I used DTOs for infrastructure layer also in domain layer. But in  normally live we always should map domain models to DTOS 
and then allow them to leave our application boundaries.


-- Contract

Our main contract that will allow every one to create any ScoreBoard using our standards. In real live this 
should be independent repository(or module/local module).

-- DataAccess
 
Storage implementation for our [FootballScoreBoard.php].In real live this
should be independent repository(or module/local module).


-- Domain
   
Our detail of implementation for FootBallScoreBoard.In real live this
should be independent repository(or module/local module).

All dependencies in real world should be injected using any kind of DI container that all 
metadata/factories will be provided in configuration file.
And injected with IOC standard. 


#Commit One 
- Prepare DTOs(also treated as domain models to simplify things and skip mapper layers)
- Prepare main contract for [ScoreBoard.php]
- Prepare in mocked [FootballScoreBoard.php] with empty methods ready to implement.
- Prepare [ScoreBoardStorage.php] interface for outside communication with any data storage for our games.
