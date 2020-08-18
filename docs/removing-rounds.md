## Moving Rounds up one level
Rounds are currently a subject of many relationships in the application. Removing the concept of Rounds (or moving it up between Competitions and Divisions) would require changes to the following relationships and pages.

If we now think of a Competition as a series of Rounds, and a Round as many Divisions, how would these relationships change?

### Round -> Penalty (pivot on choir)
This is the penalty applied to a choir per-round. There's also a penalty attached to a division. Does this mean that the penalty is available to be applied to any choir in any divison or is it applied to all choirs in the division automatically? Do we want future penalties to apply to a Division per round?

### Round -> Choir (pivot on performance order)
Since this is just the choirs linked to a round, this would probably be moved to the division.

### Round -> Comment (feedback)
These are all the comments applied to any performances in the round. We could leave this and also add one for Division. Seems to just be a subquery.

### Round -> Round (this is the relationship that links rounds as a target and source or parent and child)
We could replace this parent/child relationship by adding a pivot table between competition and round, with info such as the ordering.
gk
### Good news for data cleanup
The standings seem to already be linked to both round and division. That's not efficient (since a round has a division already) but it does help us keep the relationship between standings and divisions even though we may put a round between a division and a competition.

### Caption Weightings and Scoring Methods
These are currently linked to a Division. If we're talking about keeping a single scoresheet for a round, we could move them up. Though Caption Weightings seem to be Division specific.  Maybe the caption weightings would stay  the same and the scoresheet would be a round-specific relationship.

### What is the Division / Solo Division separation all about?
Is it that there are different scoring methods available? The categories? Lack of sponsors? This seems like a good candidate for the polymorphism used elsewhere.
