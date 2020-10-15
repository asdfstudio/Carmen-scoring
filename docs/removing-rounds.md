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
We could replace this parent/child relationship by adding a pivot table between competition and round, with info such as the ordering. NB: Choir ordering is performance_order, now in choir_division.

### Good news for data cleanup
The standings seem to already be linked to both round and division. That's not efficient (since a round has a division already) but it does help us keep the relationship between standings and divisions even though we may put a round between a division and a competition.

### Caption Weightings and Scoring Methods
These are currently linked to a Division. If we're talking about keeping a single scoresheet for a round, we could move them up. Though Caption Weightings seem to be Division specific.  Maybe the caption weightings would stay  the same and the scoresheet would be a round-specific relationship.
Sherman's Response: Captions are actually scoresheet specific. All divisions in a round should have the same scoresheet, weighting, method, and judges and their assigned captions. That way all divisions can be equally comparable to qualify for the finals round.

AJ: All "Scoring Settings" would be per-round, including the sheet itself, caption weighting, and scoring method. The optional Rating Systems and Award Settings would still be tied to a Division.

AJ: Rounds can't be optional but should have a sensible default. If a round has only a single Division, it should be called a "Set" and has its own scoring properties

AJ: Judges should be the same across all divisions in a Round.  Their captions should also be the same.

### Private Rounds
Some rounds are scored but should not have the results made public. Add an "is_exhibition" flag to these private rounds or think of an overarching privacy setting for a round to fold Solo Divisions in with others (scoring methods permitting).

### What is the Division / Solo Division separation all about?
Is it that there are different scoring methods available? The categories? Lack of sponsors? This seems like a good candidate for the polymorphism used elsewhere.
Sherman's Response: When the solo divisions were created, there was a concern of keeping and publishing information about minors and their personal scoring data. When scores are sent to the soloist's director, the director only has access to their student's scores and comments. Only award winners are made public, not a list of all participants. It was built with maintaining privacy in mind.

### Migrating the data
To use migrations to update the data while the entities are being updated, it's best to have a database consistent with the one created by app migrations.  The updates in this branch provide a compatible database. Import the production data from the prod server.

Because some columns are missing and some are in a different order, the usual backups weren't usable. The command to get an importable backup (just data, full inserts with column names) is:

      mysqldump -n -t -u root -p --skip-extended-insert --ignore-table-data=showchoir.migrations --ignore-table-data=showchoir.comments_backup --ignore-table-data=showchoir.comment_urls_backup showchoir > showchoir.prod.data.sql


## Changes to be made

### Database / Entities

- [X] Division - change competition_id to round_id.
- [X] Round - remove division_id - relationship is other way. be sure to write these out first.
- [ ] Round - remove source and target - update sequences if they're not already up-to-date.
- [ ] RoundConnection - remove, make sure relationships are preserved.
- [ ] Standings - have a division and a round. Make sure the division is the primary link. Maybe remove round. NB: The Scoring listener works on rounds, checks division to see if it is the final division in the round.
- [ ] Raw Scores - have a division and a round. Remove the round link?
- [X] Round and Division - move caption_weighting_id, scoring_method_id, sheet_id up to round so that it's consistent across divisions.  Move max_choirs down to division. 
- [ ] Division Penalty - move this up to a competition or leave it at an org. Just see where this can be fixed in the UI to look up a few levels. Chop out an org-penalty API if necessary.
- [ ] ChoirRound - Merge with ChoirDivision.  Just determines choir ordering and link to scoresheets and penalties.

### Classes

These files in app seem to use the source/target relationship currently:

- [ ] Events/RoundScoringCompleted.php
- [ ] Forms/Round/CreateRoundForm.php
- [ ] Http/Controllers/Judge/CompetitionDivisionRoundController.php
- [X] Http/Controllers/Organizer/CompetitionDivisionRoundController.php
- [ ] Http/Controllers/ResultsController.php
- [ ] Http/Controllers/ResultsController.php
- [ ] Listeners/AddChoirToRound.php
- [ ] Listeners/RemoveChoirFromRound.php
- [ ] Listeners/SyncRoundChoirs.php
- [ ] Listeners/SyncRoundChoirsFromDivision.php
- [ ] Listeners/SyncRoundChoirsFromSources.php
- [ ] Listeners/SyncRoundChoirsToTarget.php
- [ ] Listeners/SyncRoundChoirsToTarget.php
- [X] Policies/RoundPolicy.php
- [X] Policies/DivisionPolicy.php

### Templates

- [ ] competition_division_round/judge/recording_summary.blade.php
- [ ] competition_division_round/judge/recording_summary.blade.php
- [ ] competition_division_round/judge/summary.blade.php
- [ ] competition_division_round/judge/summary.blade.php
- [X] competition_division_round/organizer/list.blade.php
- [X] competition_division_round/organizer/list.blade.php
- [ ] layouts/public_results.blade.php
- [ ] layouts/public_results.blade.php
- [ ] scores/judge/spreadsheet.blade.php


### UI Overview changes
- [ ] Move the "Settings" tab from Division to a new "Round" manage index. Move the "edit scoring settings" and underlying top part of the form. Change to "edit optional rating systems" and just have that part remaining. 
- [X] Remove "Rounds" tab from the Division settings page
### Scoring

Most scoring is done per-division so that won't change. But there are parts of the scoring system that grade source Rounds for the sake of seeding Target rounds. This will all be manual from now on so it should be removed from the app.

### Tests

- [ ] As a judge, do my scores and comments from a Choir in a Division appear in the same place?
- [ ] As an organizer, can I find the relationship between a Prelim and a Final?
- [ ] Do the public results pages looks the same?
