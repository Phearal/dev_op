<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordhasher){
        $this->passwordHasher = $passwordhasher;
    }

    public function load(ObjectManager $manager): void
    {
        // USERS
        $users = [
            [
                "nickname" => "Toto",
                "email" => "toto@toto.fr",
                "password" => "toto",
                "roles" => ["ROLE_ADMIN"]
            ],
            [
                "nickname" => "Tata",
                "email" => "tata@tata.fr",
                "password" => "tata",
                "roles" => []
            ],
            [
                "nickname" => "Dave the dev",
                "email" => "super-dave@gmail.com",
                "password" => "dave",
                "roles" => []
            ]
        ];

        $user_objects = [];
        foreach ($users as $user) {
            $new_user = new User;
            $new_user->setNickname($user["nickname"]);
            $new_user->setEmail($user["email"]);
            $plaintextPassword = $user["password"];
            $new_user->setRoles($user["roles"]);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $new_user,
                $plaintextPassword
            );
            $new_user->setPassword($hashedPassword);
            $user_objects[] = $new_user;
            $manager->persist($new_user);
        }

        // TAGS
        $tags = ["Php", "Javascript", "Python", "Symfony", "Django", "React", "News", "Tutorial", "AI", "Tutorial", "Project Management"];
        $tag_objects = [];
        foreach ($tags as $tag) {
            $new_tag = new Tag();
            $new_tag->setName($tag);
            $tag_objects[$tag] = $new_tag;
            $manager->persist($new_tag);
        }

        // ARTICLES
        $articles = include __DIR__ . '/articles.php';

        foreach ($articles as $article) {
            $new_article = new Article;
            $new_article->setTitle($article["title"]);
            $new_article->setIntroduction($article["introduction"]);
            $new_article->setImage($article["image"]);
            $new_article->setContent($article["content"]);
            $new_article->setAuthor($user_objects[0]);

            foreach ($article["tags"] as $article_tag) {
                if (isset($tag_objects[$article_tag])) {
                    $new_article->addTag($tag_objects[$article_tag]);
                }
            }

            $manager->persist($new_article);
        }

        $manager->flush();
    }
}
