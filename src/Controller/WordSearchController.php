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
    public function getScore(Request $request): JsonResponse
    {
        $word = $request->query->get('term');
        $db_reader = new DatabaseReader();
        $repository = $this->getDoctrine()->getRepository(Words::class);
        $res = $db_reader->search_word_db($word, $repository);

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

        $score = $this->calculate_score($positive, $negative);

        return $this->write_output($word, $score);
    }

    private function calculate_score($positive, $negative): float
    {
        $score = 0;
        if ($positive + $negative > 0) {
            $score = number_format((float)($positive / ($positive + $negative) * 10), 2);
        }
        return $score;
    }

    private function write_output($word, $score): JsonResponse
    {
        if ($score) {
            return JsonResponse::create(array('term' => $word, 'score' => $score));
        } else {
            return JsonResponse::create(array('message' => 'No results for word ' . $word));
        }
    }
}
