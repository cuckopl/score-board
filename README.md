# score-board

All commits are in MASTER branch. And all work was done there.

**Football World Cup Score Board**

For simplicity, I used DTOs for infrastructure layer also in domain layer. But in normally live we always should map
domain models to DTOS and keep them separated and hidden. All DTOs are implemented as immutable. To prevent any
indirect changes (that we leve some reference that can modify its state).

# -- Contract

Our main contract that will allow every one to create any ScoreBoard using our standards. In real live this
should be independent repository(or module) that just keeps the contract outside any detail of implementation.
DTOs are only part of the contract.

**pros**

- If our default implementation doesn't fit any one can write own based on [ScoreBoard.php] contract and [Dto] that are
  created for that purpose. 
- We depend on abstraction
- [Contract] whole contract doesn't depend on any external dependencies.

**cons**
- Extra repository that needs to be maintained
- Always monitor if we don't have any dependency from outside (https://www.archunit.org/ is for Java, https://github.com/j6s/phparch is for PHP).


# -- DataAccess

Storage implementation for our [ScoreBoardStorage.php]([InMemoryStorage.php]). It should be always out from domain. It is detail of
implementation that is connected with some infrastructure (or at least almost of times we use some external systems or
libraries that we don't want to depend on it in business logic).

# -- Domain
Our detail of implementation for FootBallScoreBoard.

All dependencies in real world should be injected using any kind of DI container that all
metadata/factories will be provided in configuration file.And injected with IOC .
We even give some flexibility in current [FootballScoreBoard.php] implementation for example we exposed [ScoreBoardStorage.php]. 
We can add even more if we decide to. Like extract [ValidatorPipeline.php] in to interface and allow any one to use custom validators.
In worst case scenario user will be forced to write own implementation.

Our Domain package will provide implementation of [ScoreBoard] interface.
And will need to inject implementation of [ScoreBoardStorage] using IOC. For this example configuration of IOC an DI was
skipped to keep project short(as this some configuration and way how to load classes). If we use IOC Domain will depend only on [Contract] 
(because class loader will provide concrete implementation from DI container) abstraction.

From our domain model we should only inject [FootballScoreBoard] to [ScoreBoard.php] and use DTOS. Everything else should be hidden.

The test that gathers everything is here [FootballScoreBoardFullTest.php]; 

Thank you for the opportunity to implement this! 
I am open to any advices as everything can be better.








