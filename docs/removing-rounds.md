## Moving Rounds up one level
Rounds are currently a subject of many relationships in the application. Removing the concept of Rounds (or moving it up between Competitions and Divisions) would require changes to the following relationships and pages.

If we now think of a Competition as a series of Rounds, and a Round as many Divisions, how would these relationships change?

### Round -> Penalty (pivot on choir)
This is the penalty applied to a choir per-round. There's also a penalty attached to a division. Does this mean that the penalty is available to be applied to any choir in any divison or is it applied to all choirs in the division automatically? Do we want future penalties to apply to a Division per round?
Sherman's Response: I am not sure why it was built this way, but right now an organization creates a penalty that has a few settings. This penalty is then added to this organization's penalty database for future use. In order to apply a penalty, it has to be added to a division. From there it can be applied to a choir. Penalties are only applied to individual choirs, not to entire divisions. In reality, the division doesn't need to be involved.

### Round -> Choir (pivot on performance order)
Since this is just the choirs linked to a round, this would probably be moved to the division.

### Round -> Comment (feedback)
These are all the comments applied to any performances in the round. We could leave this and also add one for Division. Seems to just be a subquery.
Sherman's Response: Not sure if this matters, but since there is only ever one performance for each participant in any given round, we could just leave it and not worry aboout the division... maybe?

### Round -> Round (this is the relationship that links rounds as a target and source or parent and child)
We could replace this parent/child relationship by adding a pivot table between competition and round, with info such as the ordering.
gk
### Good news for data cleanup
The standings seem to already be linked to both round and division. That's not efficient (since a round has a division already) but it does help us keep the relationship between standings and divisions even though we may put a round between a division and a competition.

### Caption Weightings and Scoring Methods
These are currently linked to a Division. If we're talking about keeping a single scoresheet for a round, we could move them up. Though Caption Weightings seem to be Division specific.  Maybe the caption weightings would stay  the same and the scoresheet would be a round-specific relationship.
Sherman's Response: Captions are actually scoresheet specific. All divisions in a round should have the same scoresheet, weighting, method, and judges and their assigned captions. That way all divisions can be equally comparable to qualify for the finals round.

AJ: All "Scoring Settings" would be per-round, including the sheet itself, caption weighting, and scoring method. The optional Rating Systems and Award Settings would still be tied to a Division.

### What is the Division / Solo Division separation all about?
Is it that there are different scoring methods available? The categories? Lack of sponsors? This seems like a good candidate for the polymorphism used elsewhere.
Sherman's Response: When the solo divisions were created, there was a concern of keeping and publishing information about minors and their personal scoring data. When scores are sent to the soloist's director, the director only has access to their student's scores and comments. Only award winners are made public, not a list of all participants. It was built with maintaining privacy in mind.
