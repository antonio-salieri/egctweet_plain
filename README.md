# egctweet_plain
Tweet viewer (same as project https://github.com/antonio-salieri/egctwit) but without use of framework

This is simple ZF2 application which shows last five user tweets.

To install from composer type the following command:

php composer.phar create-project -sdev "antonio-salieri/egctweet_plain": "dev-master" <path/to/project/directory>

After that you should edit file "config.php" in "config/" directory, and put Twitter application access keys in right place in this file.

If you haven't created Twitter app yet go to https://apps.twitter.com and create one, then put keys for application access in "config/config.php" file.
