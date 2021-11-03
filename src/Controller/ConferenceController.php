<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    private Environment $twig;

    public function __construct (Environment $twig)
    {
        $this->twig=$twig;
    }
    #[Route('/', name: 'homepage_new')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
////        return $this->render('conference/index.html.twig', [
//            'controller_name' => 'ConferenceController',
//        ]);

//        $greet = '';
//        if ($name) {
//            $greet = sprintf("<h1>Hello, %s!</h1>", htmlspecialchars($name));
//        }


//        return new Response(<<<EOF
//<html>
//    <body>
//$greet
//<img src="images/burger_king_po_Ukraine.jpg" width="250" height="250" alt="Топ картинка"/>
//    </body>
//</html>
//EOF
//);
        return new Response($this->twig->render('conference/index.html.twig', [
            'conferences'=>$conferenceRepository->findAll(),
        ]));
    }
    #[Route ('/conference/{id}', name: 'conference_id')]
    public function show (Request $request, Conference $conference, CommentRepository $commentRepository, ConferenceRepository$conferenceRepository)
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conferences'=>$conferenceRepository->findAll(),
            'conference'=>$conference,
//               'comments'=>$commentRepository->findBy([
//                   'conference'=>$conference
//               ], [
//                   'createdAt'=>'DESC'
//               ])
            'comments'=>$paginator,
            'previous'=>$offset- CommentRepository::PAGINATOR_PER_PAGE,
            'next'=>min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE)

            ]));
    }
}
