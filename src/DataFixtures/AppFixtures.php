<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
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
        $toto = new User;
        $toto->setEmail('toto@toto.fr');
        $toto->setNickname('Toto');
        $plaintextPassword = "toto";
        $toto->setRoles(["ROLE_ADMIN"]);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $toto,
            $plaintextPassword
        );
        $toto->setPassword($hashedPassword);
        $manager->persist($toto);

        $tata = new User;
        $tata->setEmail('tata@tata.fr');
        $tata->setNickname('Tata');
        $plaintextPassword = "tata";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $tata,
            $plaintextPassword
        );
        $tata->setPassword($hashedPassword);
        $manager->persist($tata);

        $article1 = new Article;
        $article1->setTitle("Dave{x} la start-up qui décolle");
        $article1->setIntroduction("LES DAVE réunit les meilleurs développeurs et talents du digital. Une vision commune : l'excellence, un sens aiguisé du détail et la volonté de mener à bien les plus beaux projets. Une étoile du Nord : atteindre la perfection sans renoncer à la réactivité et adaptation indispensable à chaque projet.");
        $article1->setImage("daveslesdevs.png");
        $article1->setContent("Sur les réseaux sociaux, « Dave le développeur » est un personnage culte. Un jeune travailleur libre, heureux, intouchable, courtisé par toutes les entreprises qui se battent pour le recruter. Devenu un mème d’Internet, « Dave le dev », peut même se permettre de se moquer des dizaines d’offres d’emploi qui pleuvent sur lui tout au long de la journée. « C’est pas moi j’postule à ton offre, c’est ton offre elle postule à moi », dit-il ainsi en ouvrant le réseau social professionnel LinkedIn le matin, sur l’une des centaines d’images allégoriques qui circulent sur Facebook, Twitter, Instagram, LinkedIn, YouTube, Twitch et même le service de discussion Discord. Une référence au couplet culte de la chanson DKR du rappeur Booba (« C’est pas le quartier qui me quitte/C’est moi j’quitte le quartier »). Un dessin satirique, pas si loin de la réalité. Une pluie de messages de recruteurs, c’est aussi ce que reçoit Wilfried Evieux chaque semaine sur LinkedIn. Avec trois à quatre demandes par jour, ce développeur Web de 26 ans diplômé de l’école Supinfo Paris travaille aujourd’hui en free-lance. Alors qu’il était encore en formation, il a rapidement compris l’attrait que son profil avait pour les entreprises. Avec quelques années d’expérience à son compteur, les demandes affluent toujours plus. Même en spécifiant qu’il n’est pas en recherche d’emploi, les entreprises essaient constamment de le débaucher, à l’image de « Dave le dev ». Et pour cause, ce métier mal connu est aujourd’hui un pilier du Web. A partir d’un cahier des charges, le développeur analyse les besoins, choisit la solution technique la mieux adaptée et développe les fonctionnalités du site ou de l’application Web, en les codant. Un développeur débutant gagne un salaire annuel d’environ 32 000 à 48 000 euros annuels. Avec deux à cinq années d’expérience, la rémunération oscille entre 38 000 et 55 000 euros pour les profils confirmés. Si les développeurs ont des profils et des compétences qui attirent autant c’est que le numérique représente aujourd’hui 5,5 % du PIB français, chiffre qui pourrait doubler d’ici quelques années, selon une étude du cabinet McKinsey reprise par Bpifrance.");
        $manager->persist($article1);

        $article2 = new Article;
        $article2->setTitle("Les développeurs Web, « rock stars » du marché de l’emploi, craignent de devenir les « ouvriers d’hier »");
        $article2->setIntroduction("Toujours très convoités et choyés par les recruteurs, les développeurs Web considèrent que leur position avantageuse est arrivée à un point d’équilibre. Certains craignent de se laisser dépasser par l’intelligence artificielle et les évolutions technologiques.");
        $article2->setImage("daveledev.jpeg");
        $article2->setContent("Sur les réseaux sociaux, « Dave le développeur » est un personnage culte. Un jeune travailleur libre, heureux, intouchable, courtisé par toutes les entreprises qui se battent pour le recruter. Devenu un mème d’Internet, « Dave le dev », peut même se permettre de se moquer des dizaines d’offres d’emploi qui pleuvent sur lui tout au long de la journée. « C’est pas moi j’postule à ton offre, c’est ton offre elle postule à moi », dit-il ainsi en ouvrant le réseau social professionnel LinkedIn le matin, sur l’une des centaines d’images allégoriques qui circulent sur Facebook, Twitter, Instagram, LinkedIn, YouTube, Twitch et même le service de discussion Discord. Une référence au couplet culte de la chanson DKR du rappeur Booba (« C’est pas le quartier qui me quitte/C’est moi j’quitte le quartier »). Un dessin satirique, pas si loin de la réalité. Une pluie de messages de recruteurs, c’est aussi ce que reçoit Wilfried Evieux chaque semaine sur LinkedIn. Avec trois à quatre demandes par jour, ce développeur Web de 26 ans diplômé de l’école Supinfo Paris travaille aujourd’hui en free-lance. Alors qu’il était encore en formation, il a rapidement compris l’attrait que son profil avait pour les entreprises. Avec quelques années d’expérience à son compteur, les demandes affluent toujours plus. Même en spécifiant qu’il n’est pas en recherche d’emploi, les entreprises essaient constamment de le débaucher, à l’image de « Dave le dev ». Et pour cause, ce métier mal connu est aujourd’hui un pilier du Web. A partir d’un cahier des charges, le développeur analyse les besoins, choisit la solution technique la mieux adaptée et développe les fonctionnalités du site ou de l’application Web, en les codant. Un développeur débutant gagne un salaire annuel d’environ 32 000 à 48 000 euros annuels. Avec deux à cinq années d’expérience, la rémunération oscille entre 38 000 et 55 000 euros pour les profils confirmés. Si les développeurs ont des profils et des compétences qui attirent autant c’est que le numérique représente aujourd’hui 5,5 % du PIB français, chiffre qui pourrait doubler d’ici quelques années, selon une étude du cabinet McKinsey reprise par Bpifrance.");
        $manager->persist($article2);

        $manager->flush();
    }
}
