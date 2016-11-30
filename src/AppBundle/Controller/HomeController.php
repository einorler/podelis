<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $usercount = $this->get('app.user')->userCount();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Book')->findAll();

        return $this->render('AppBundle:Home:index.html.twig', [
            'usercount' => $usercount,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/profile", name="user")

     */
    public function userAction(Request $request)
    {
        //* @Security("has_role('ROLE_USER')")
        
        return $this->render('AppBundle:Home:user.html.twig', []);
    }


    /**
     * @Route("/leaderboard/{page}", name="leaderboard")
     */
    public function leaderboardAction(Request $request, $page = 1)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $orderParams = $request->query->all();

        $best = $repository->findBest($orderParams, $page, $limit = 2);
        $maxPages = ceil($best->count() / $limit);

        if($page > $maxPages){
            return $this->render('@App/Home/404.html.twig');
        }

        return $this->render('@App/Home/leaderboard.html.twig', [
            'thisPage' => $page,
            'maxPages' => $maxPages,
            'best' => $best,
            'limit' => $limit
        ]);
    }

    /**
     * @Route("/failures", name="failures")
     */
    public function failuresAction()
    {
        return $this->render('AppBundle:Home:failures.html.twig', [
        ]);
    }

    /**
     * @Route("/api/user/data", name="user-data")
     */
    public function userDataAction(Request $request)
    {
        $securityContext = $this->get('security.authorization_checker');
        /** @var User $user */
        $user = $this->getUser();

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')
        ) {
            $res=["0","10","20","30","40","50","60","70","80"];
            $data = "var data = google.visualization.arrayToDataTable([
                            ['Categories', 'Results', { role: 'style' }, { role: 'annotation' } ],
                            ['Strings', $res[0], 'color: gray', 'Strings'],
                            ['Security', $res[1], 'color: #76A7FA', 'Security'],
                            ['Inpute/output', $res[2], 'opacity: 0.2', 'asfgasfas'],
                            ['AI', $res[3], 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF', 'asfjag'],
                            ['Basics', $res[4], 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2', 'ashjagosg'],
                            ['Security', $res[5], 'color: #76A7FA', 'asfjas'],
                            ['Inpute/output', $res[6], 'opacity: 0.2', 'ajogas'],
                            ['AI', $res[7], 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF', 'ahgoas'],
                            ['Basics', $res[8], 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2','asgagsag']
                        ]);";
            /*
            $data = "var data = google.visualization.arrayToDataTable([
                            ['Category', 'Results', { role: 'style' } ],
                            ['Strings', 10, 'color: gray'],
                            ['Security', 84, 'color: #76A7FA'],
                            ['Inpute/output', 0, 'opacity: 0.2'],
                            ['AI', 0, 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF'],
                            ['Basics', 75, 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2']
                        ]);";*/

            return new JsonResponse(json_encode(['data' => $data]));

        } else {
            return $this->redirectToRoute('homepage');
        }
    }
}

