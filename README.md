# DEV | OP
#### Video Demo:  https://www.youtube.com/watch?v=dQw4w9WgXcQ&ab
#### Description:
Dev | OP is a blog made with [Symfony](https://symfony.com/) containing articles about web developpement and dev related project management.

As a visitor you can read the article and create an account.

With a non admin account you can comment the articles and like them which adds them to your list of liked articles.

With an admin account you get access to an admin menu allowing you to CRUD articles, tags, users and comments (comments need to be validated by an admin before being visible).

#### Install:
Make sure [git](https://git-scm.com/downloads), [Symfony CLI](https://symfony.com/download) and [Composer](https://getcomposer.org/) are installed.
1. Clone the project with the command `git clone https://github.com/Phearal/dev_op2.git`.
2. Open a terminal on project root and run `composer install`.
3. Create a .env file with a link to a MySQL database `DATABASE_URL="mysql://user:password@127.0.0.1:3306/dev_op?serverVersion=8.0.32&charset=utf8mb4"`
4. Run `symfony console doctrine:database:create`
5. If you wish to use my data fixtures (more on fixtures [here](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)), run `symfony console doctrine:fixtures:load`
