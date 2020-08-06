# Carmen ShowChoir

Carmen ShowChoir is an app built on the Laravel Framework that helps organizers judge showchoir contests.

## Technologies

ShowChoir is built on Laravel v.#### with a MariaDB v.### backend.  The judging "spreadsheet" is built on Vue.js v2.5.2

## Development

The easiest way to start developing is to use the Laravel Homestead. Once you have that vagrant box set up, you'll need to recreate the cache directories and create your own .env file from the .env.example file.  Create a database on the Homestead machine and restore from a backup. The migration don't currently work to create tables because an older one is broken.

## Deployment
