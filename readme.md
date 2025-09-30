# The Carmen Scoring System

The Carmen Scoring System is an app that provides a scoring platform for adjudicated events such as showchoir, solo, concert choir, band, etc. Organizers set up the competition, divisions, and the participants within the app, judges assign scores on a selected scoresheet and give typed and/or recorded comments to each performance, results are calculated according to the chosen method, results are published on a public page, and details are sent to directors via email and text.

## Technologies

The Carmen Scoring System is built on Laravel v7 with a MariaDB v10.2.25 backend.  The judging spreadsheet is built on Vue.js v2.5.2.

## Development

The easiest way to start developing is to use the Laravel Homestead. Once you have that vagrant box set up, you'll need to recreate the cache directories and create your own .env file from the .env.example file.  Create a database on the Homestead machine and restore from a backup. The migration don't currently work to create tables because an older one is broken.

There's also a docker-compose file that will create a docker app with the database and nginx server, serving the app at http://localhost:8000.  You'll need to populate the database but it should be created using the name and password from your .env file.

Assets are processed by Laravel Mix and written to /public/dist. If you need to edit any assets, do so in the /resources folder.  You can build the javascript and sass by using npm tasks, for example:

    npm run watch

is a task to build the app and trigger a new build on changes.  When you need to deploy, use the prod command to write minimized and concatenated files:

    npm run prod

## Deployment

Deployment is done via a "git pull" in the appropriate directory on the server. Use the ssh key for the "carmen" user, and you have read-only access to the bitbucket directory via the command line.

- The "dev" branch is deployed to /home/carmen/test and is available at https://test.carmenscoring.com/login
- The "master" branch is deployed to /home/carmen/showchoir and is available at https://showchoir.carmenscoring.com/login

There is no automated process to run composer so be sure to manually do so if you've upgraded any of the PHP libraries. If you update the judging spreadsheet, you will likely need to use npm to build the application before checking it in to git.

## Tests

The Carmen Scoring System is built with testing. It is using PHPUnit and a phpunit.xml file is already setup for this application.

There is /tests directory that contains three sub-directories(Feature, Unit, and Pages). Feature and Unit are default directory and Pages is custom directory to add pages tests.

You are free to define other testing environment configuration values as necessary. The testing environment variables may be configured in the phpunit.xml file, but make sure to clear your configuration cache using the config:clear Artisan command before running your tests!

In addition, you may create a .env.testing file in the root of your project. This file will override the .env file when running PHPUnit tests or executing Artisan commands with the --env=testing option.


## 👥 Team

**Developed by Airly Studio**

- **[Taraqul Islam Rony](https://github.com/TIRony)** - *Senior Full Stack Engineer*
- **[Sakil Sazzad Joy](https://github.com/ss-joy)** - *Full Stack Engineer*


## 📞 Support & Contact

- **Website**: [airlystudio.com](https://airlystudio.com/)
- **Company**: Airly Studio
- **Email**: hello@airlystudio.com

For platform support, technical assistance, or project inquiries, contact our development team.

---

⭐ **Showcasing Creative Excellence | Built with precision by Airly Studio**

    php artisan test
