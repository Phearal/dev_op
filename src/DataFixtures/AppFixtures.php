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
                "nickname" => "Dave le dev",
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
        $tags = ['Php', 'Javascript', 'Python', 'Symfony', 'Django', 'React', 'Actualité', 'Tutoriel', 'IA'];
        $tag_objects = [];
        foreach ($tags as $tag) {
            $new_tag = new Tag();
            $new_tag->setName($tag);
            $tag_objects[] = $new_tag;
            $manager->persist($new_tag);
        }

        // ARTICLES
        $articles = [
            [
                "title" => "Dave{x} la start-up qui décolle",
                "introduction" => "LES DAVE réunit les meilleurs développeurs et talents du digital. Une vision commune : l'excellence, un sens aiguisé du détail et la volonté de mener à bien les plus beaux projets. Une étoile du Nord : atteindre la perfection sans renoncer à la réactivité et adaptation indispensable à chaque projet.",
                "image" => "daveledev.jpeg",
                "content" => "Sur les réseaux sociaux, « Dave le développeur » est un personnage culte. Un jeune travailleur libre, heureux, intouchable, courtisé par toutes les entreprises qui se battent pour le recruter. Devenu un mème d’Internet, « Dave le dev », peut même se permettre de se moquer des dizaines d’offres d’emploi qui pleuvent sur lui tout au long de la journée. « C’est pas moi j’postule à ton offre, c’est ton offre elle postule à moi », dit-il ainsi en ouvrant le réseau social professionnel LinkedIn le matin, sur l’une des centaines d’images allégoriques qui circulent sur Facebook, Twitter, Instagram, LinkedIn, YouTube, Twitch et même le service de discussion Discord. Une référence au couplet culte de la chanson DKR du rappeur Booba (« C’est pas le quartier qui me quitte/C’est moi j’quitte le quartier »). Un dessin satirique, pas si loin de la réalité. Une pluie de messages de recruteurs, c’est aussi ce que reçoit Wilfried Evieux chaque semaine sur LinkedIn. Avec trois à quatre demandes par jour, ce développeur Web de 26 ans diplômé de l’école Supinfo Paris travaille aujourd’hui en free-lance. Alors qu’il était encore en formation, il a rapidement compris l’attrait que son profil avait pour les entreprises. Avec quelques années d’expérience à son compteur, les demandes affluent toujours plus. Même en spécifiant qu’il n’est pas en recherche d’emploi, les entreprises essaient constamment de le débaucher, à l’image de « Dave le dev ». Et pour cause, ce métier mal connu est aujourd’hui un pilier du Web. A partir d’un cahier des charges, le développeur analyse les besoins, choisit la solution technique la mieux adaptée et développe les fonctionnalités du site ou de l’application Web, en les codant. Un développeur débutant gagne un salaire annuel d’environ 32 000 à 48 000 euros annuels. Avec deux à cinq années d’expérience, la rémunération oscille entre 38 000 et 55 000 euros pour les profils confirmés. Si les développeurs ont des profils et des compétences qui attirent autant c’est que le numérique représente aujourd’hui 5,5 % du PIB français, chiffre qui pourrait doubler d’ici quelques années, selon une étude du cabinet McKinsey reprise par Bpifrance."
            ],
            [
                "title" => "Les développeurs Web, « rock stars » du marché de l’emploi, craignent de devenir les « ouvriers d’hier",
                "introduction" => "Toujours très convoités et choyés par les recruteurs, les développeurs Web considèrent que leur position avantageuse est arrivée à un point d’équilibre. Certains craignent de se laisser dépasser par l’intelligence artificielle et les évolutions technologiques.",
                "image" => "daveslesdevs.png",
                "content" => "Sur les réseaux sociaux, « Dave le développeur » est un personnage culte. Un jeune travailleur libre, heureux, intouchable, courtisé par toutes les entreprises qui se battent pour le recruter. Devenu un mème d’Internet, « Dave le dev », peut même se permettre de se moquer des dizaines d’offres d’emploi qui pleuvent sur lui tout au long de la journée. « C’est pas moi j’postule à ton offre, c’est ton offre elle postule à moi », dit-il ainsi en ouvrant le réseau social professionnel LinkedIn le matin, sur l’une des centaines d’images allégoriques qui circulent sur Facebook, Twitter, Instagram, LinkedIn, YouTube, Twitch et même le service de discussion Discord. Une référence au couplet culte de la chanson DKR du rappeur Booba (« C’est pas le quartier qui me quitte/C’est moi j’quitte le quartier »). Un dessin satirique, pas si loin de la réalité. Une pluie de messages de recruteurs, c’est aussi ce que reçoit Wilfried Evieux chaque semaine sur LinkedIn. Avec trois à quatre demandes par jour, ce développeur Web de 26 ans diplômé de l’école Supinfo Paris travaille aujourd’hui en free-lance. Alors qu’il était encore en formation, il a rapidement compris l’attrait que son profil avait pour les entreprises. Avec quelques années d’expérience à son compteur, les demandes affluent toujours plus. Même en spécifiant qu’il n’est pas en recherche d’emploi, les entreprises essaient constamment de le débaucher, à l’image de « Dave le dev ». Et pour cause, ce métier mal connu est aujourd’hui un pilier du Web. A partir d’un cahier des charges, le développeur analyse les besoins, choisit la solution technique la mieux adaptée et développe les fonctionnalités du site ou de l’application Web, en les codant. Un développeur débutant gagne un salaire annuel d’environ 32 000 à 48 000 euros annuels. Avec deux à cinq années d’expérience, la rémunération oscille entre 38 000 et 55 000 euros pour les profils confirmés. Si les développeurs ont des profils et des compétences qui attirent autant c’est que le numérique représente aujourd’hui 5,5 % du PIB français, chiffre qui pourrait doubler d’ici quelques années, selon une étude du cabinet McKinsey reprise par Bpifrance."
            ],
            [
                "title" => "Développeur web : 10 outils IA pour générer et corriger du code",
                "introduction" => "Le code généré par IA devient de plus en plus précis et les outils qui le permettent se diversifient. Simple assistant ou générateur de A à Z : découvrez notre sélection.",
                "image" => "AI-outils-code-developpeurs.jpg",
                "content" => "L’IA permet de générer du texte, des images, de la vidéo, du son. Mais également du code. En effet, de nombreux outils permettent de créer du code, l’expliquer, le corriger, l’optimiser, le compléter. Et leurs cibles sont variées : des développeurs débutants aux experts de leur domaine, en passant par ceux qui souhaitent simplement un assistant IA et ceux qui veulent pouvoir développer leur projet via l’IA de A à Z.<br><br>En cette journée mondiale des programmeurs et développeurs, nous vous proposons une sélection d’outils qui vous assisteront dans vos tâches de programmation et de développement grâce à l’intelligence artificielle !<br><br><strong>1. GitHub Copilot</strong><br><br>GitHub Copilot est un outil pour vous assister pendant que vous codez. Il peut vous suggérer des éléments d’autocomplétion lorsque vous commencez à rédiger du code, mais également répondre à vos requêtes textuelles qui décrivent ce que vous souhaitez que le code réalise comme action. Alimenté par un modèle d’IA générative conçu par GitHub, OpenAI et Microsoft, cet assistant IA est disponible en extension pour Visual Studio Code, Neovim, JetBrains, Azure Data Studio, et bien d’autres. GitHub Copilot comprend de nombreux langages de programmation, dont JavaScript qu’il maîtrise particulièrement bien, mais aussi C, C++, C#, PHP, Python, Ruby, etc.<br>En outre, une version actuellement nommée GitHub Copilot X est en cours de développement. Elle s’appuie sur GPT-4 et offre la possibilité, grâce à son chat et son terminal, de gérer les pull requests, corriger du code, le traduire, écrire des tests unitaires et fournir des réponses personnalisées à partir de la documentation.<br><br><strong>2. Replit Ghostwriter</strong><br><br>Replit Ghostwiter permet la génération de code, sa complétion, sa transformation, son amélioration, son décryptage ou son débogage. La liste des langages de programmation pris en charge n’est pas négligeable : Bash, C, C++, C#, CSS, Go, PHP, JavaScript, Java, PHP, Perl, Python, Ruby… Et la liste ne cesse de croître !Vous pourrez rapidement utiliser différents frameworks, API et langages, car Replit Ghostwriter propose un IDE en ligne. L’outil est ainsi particulièrement adapté aux codeurs en herbe, qui souhaitent s’initier à la programmation grâce au soutien et aux explications de l’intelligence artificielle.<br><br><strong>3. Code Llama</strong><br><br>Meta a lancé son modèle de langage open source destiné au code. Basé sur son grand modèle de langage Llama 2, Code Llama est spécialement conçu pour la programmation et le développement : il permet la génération de code et la production en langage naturel, en réponse à des requêtes en code ou en langage naturel. Sa flexibilité le rend adaptable à de nombreux langages de programmation, comme Python, C++, Java, PHP, C# ou encore Bash. D’ailleurs, Meta propose son outil en plusieurs versions spécifiques, dont une dédiée à Python et une autre affinée pour comprendre et générer des réponses en langage naturel à partir d’instructions. À noter que Code Llama est gratuit pour la recherche et l’usage commercial.<br><br><strong>4. Tabnine</strong><br><br>Si Tabnine n’est pas un générateur de code à proprement parler, c’est-à-dire end-to-end, il permet d’optimiser la fonction d’autocomplétion de votre IDE. Il est compatible avec plus de 20 langages de programmation et 15 éditeurs, dont VS Code, IntelliJ, Android Studio ou encore Vim. De plus, Tabnine propose une sécurité et une confidentialité adaptées pour les entreprises. Les contrôles offerts par chaque solution garantissent que le code reste sûr, privé et sécurisé.<br><br><strong>5. CodeT5</strong><br><br>CodeT5 est un modèle de langage de programmation open source développé par Salesforce. Il se base sur le principe T5 (text-to-text transfer transformer) de Google. Il comprend un grand nombre de data sets, l’équipe ayant extrait plus de 8 millions d’instances de code accessibles au grand public depuis GitHub. Ainsi, CodeT5 permet de générer du code à partir de descriptions en langage naturel. Il dispose également de capacités d’autocomplétion et de synthèse, permettant le résumé d’une fonction en langage naturel.<br><br><strong>6. CodeGPT</strong><br><br>CodeGPT est une extension tierce développée pour VS Code. Grâce à votre propre clé API, vous pourrez utiliser les modèles GPT, dont GPT-4, au sein de votre application. Avec cet outil, vous allez pouvoir générer du code, sélectionner des extraits pour que l’IA en explique la fonction, dénicher des erreurs ou des problèmes, trouver des solutions ou encore retoucher votre code. 16 langages de programmation sont pris en compte et il vous sera possible de lier l’outil à d’autres API de services d’IA, comme HuggingFace ou Anthropic par exemple.<br><br><strong>7. Amazon CodeWhisperer</strong><br><br>Amazon s’est également lancé dans la course à l’IA et propose un outil dédié à la programmation : CodeWhisperer. En rédigeant une requête, vous allez pouvoir obtenir des fonctions complètes basées sur votre code. L’outil peut également analyser votre code pour mettre en évidence d’éventuelles vulnérabilités. Mais surtout, CodeWhisperer peut être utilisé dans de nombreux IDE, comme VS Code, IntelliJ et d’autres, tout en prenant en charge plusieurs langages de programmation comme Python, JavaScript, Typescript, C#, entre autres.<br><br><strong>8. GPT Engineer</strong><br><br>Projet open source, Code Engineer est une IA similaire à Auto-GPT, qui vous permet de construire l’ensemble de la base de code d’un projet. En utilisant une API GPT, vous allez pouvoir décrire à l’outil ce que vous voulez faire. Puis, l’IA vous posera des questions sur votre projet et commencera sa construction. De fait, Code Engineer a la capacité de concevoir des applications complètes, en fournissant simplement une série d’instructions.<br><br><strong>9. ChatGPT</strong><br><br>On ne présente plus ChatGPT, dont l’actuel modèle, GPT-4, présente d’excellentes compétences de code. L’outil d’OpenAI permet de générer, expliquer, déboguer et corriger du code, tout en l’expliquant. Il s’agit, selon HumanEval, de la meilleure IA pour coder avec Python, ce qui en fait un excellent outil pour les développeurs débutants comme pour les experts. De plus, avec l’abonnement ChatGPT Plus, vous disposerez non seulement de GPT-4 et de son API, mais également du plugin Code Interpreter, qui vous permet d’analyser et d’interpréter des lignes de codes.<br><br><strong>10. Google Bard</strong><br><br>Chatbot généraliste, Google Bard montre de très bonnes capacités en termes de génération de code. Le modèle PaLM 2 permet la prise en charge de plus de 20 langages de programmation, dont Python, C, C++, JavaScript, etc. Mais Bard peut également traduire du code, l’exécuter, et vous donne la possibilité d’exporter la production directement dans Google Colab. L’IA pourra aussi résoudre des bugs, expliquer des extraits de code ou demander l’écriture de fonctions pour Google Sheets."
            ],
        ];

        foreach ($articles as $article) {
            $new_article = new Article;
            $new_article->setTitle($article["title"]);
            $new_article->setIntroduction($article["introduction"]);
            $new_article->setImage($article["image"]);
            $new_article->setContent($article["content"]);
            $new_article->setAuthor($user_objects[0]);

            $number_of_tags = random_int(1, 6);
            $article_tags = [];
            for ($i = 0 ; $i < $number_of_tags ; $i++) {
                $random_tag = $tag_objects[random_int(1, count($tag_objects)-1)];
                if (!in_array($random_tag, $article_tags)) {
                    $article_tags[] = $random_tag;
                    $new_article->addTag($random_tag);
                }
            }

            $manager->persist($new_article);
        }

        $manager->flush();
    }
}
