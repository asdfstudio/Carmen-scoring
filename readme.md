# The Carmen Scoring System

The Carmen Scoring System is an app that provides a scoring platform for adjudicated events such as showchoir, solo, concert choir, band, etc. Organizers set up the competition, divisions, and the participants within the app, judges assign scores on a selected scoresheet and give typed and/or recorded comments to each performance, results are calculated according to the chosen method, results are published on a public page, and details are sent to directors via email and text.

## Technologies

The Carmen Scoring System is built on Laravel v5.7.29 with a MariaDB v10.2.25 backend.  The judging spreadsheet is built on Vue.js v2.5.2.

## Development

The easiest way to start developing is to use the Laravel Homestead. Once you have that vagrant box set up, you'll need to recreate the cache directories and create your own .env file from the .env.example file.  Create a database on the Homestead machine and restore from a backup. The migration don't currently work to create tables because an older one is broken.

There is a gulp task to process the sass and js files from /resources and build them to /public. Don't edit files in /public because your changes will be overwritten by the gulp task. You can install the necessary node modules by running

    npm install

in the root directory of the project. While developing, you can run

    npm run gulp

to keep building css and js while you're working. When you want to deploy, you can do a build by running:

    npx gulp build-js
    npx gulp sass

to run the appropriate jobs.

## Deployment

Deployment is done via a "git pull" in the appropriate directory on the server. Use the ssh key for the "carmen" user, and you have read-only access to the bitbucket directory via the command line.

- The "dev" branch is deployed to /home/carmen/test and is available at https://test.carmenscoring.com/login
- The "master" branch is deployed to /home/carmen/showchoir and is available at https://showchoir.carmenscoring.com/login

There is no automated process to run composer so be sure to manually do so if you've upgraded any of the PHP libraries. If you update the judging spreadsheet, you will likely need to use npm to build the application before checking it in to git.
