# Project description #

This is a Somtel project based on IT Girnos Mill Framework. Project is built with **Symfony3**.

# Getting started #

## Prerequisites ##

[Composer](https://getcomposer.org/)

## Steps ##

1. Clone repository to your local system.

2. Go to your project directory root folder.
        
3. Install vendor files through composer

        composer install
        
    After a while you will be prompted to configure your local parameters. Do that.

4. Create your configured database, create schema and load fixtures for testing

        ./c d:d:c
        ./c d:s:c
        ./c d:f:l
        
    In an unfortunate event you are using windows just replace `./c` with `php bin/console`

# Development rules #

1. Project has 3 main branches:

    * master - fully working and documented code deployed / deployable on client's servers
    * stage - fully working and documented code in testing / confirm / review
    * dev - mostly working code for other developers to pull the current code

2. Each feature / fix is developed in their own branches, prefixed with their respective category, like so:
    
    * `feature/payment-window`
    * `fix/user-loading-screen`
    
3. One commit per logical change! If you forgot something, ***don't*** create a new commit. Instead amend changes to your last commit.

4. Commit messages should be written according to [official GIT documentation](https://git-scm.com/book/ch5-2.html) and [this post](http://chris.beams.io/posts/git-commit/)
    
5. New branches should only be branched from `dev` branch. 

6. After completing a feature, submit a pull request to dev branch with a check to close your branch after merge.