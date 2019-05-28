<?php

namespace App\Controller;

use App\Entity\Words;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class WordSearchController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager, SearchInterface $searcher)
    {
        $this->entityManager = $entityManager;
        $this->searcher = $searcher;

    }

    /**
     * @Route("/score/", name="term_score")
     */
    public function getScore(Request $request):JsonResponse
    {
        $word = $request->query->get('term');
        $db_reader = new DatabaseReader();
        $repository = $this->getDoctrine()->getRepository(Words::class);
        $res = $db_reader->search_word_db($word, $repository);
        //$searcher = new GitHubSearcher();

        if (!$res) {
            $positive = $this->searcher->getRocks($word);
            $negative = $this->searcher->getSucks($word);
            $request = array("word" => $word, "positive" => $positive, "negative" => $negative);
            $write_db = new DatabaseWriter();
            $write_db->insert_word($request, $this->entityManager);

        } else {
            $positive = $res->getPositive();
            $negative = $res->getNegative();
        }

        $score = 0;

        $total = $positive + $negative;
        if ($total){
            $score = $this->calculate_score($positive, $total);
        }

        return $this->write_output($total, $word, $score);
    }

    private function write_output($total, $word, $score) : JsonResponse
    {
        if ($total) {
            return JsonResponse::create(array('term' => $word, 'score' => $score));
        } else {
            return JsonResponse::create(array('message' => 'No results for word ' . $word));
        }
    }

    private function calculate_score($positive, $total) :float {
        $score = (float)($positive / ($total) *10);
        return $score;

    }

}
