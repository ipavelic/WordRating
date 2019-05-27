<?php

namespace App\Controller;

use App\Entity\Words;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GitHubSearchController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/score/", name="term_score")
     */
    public function get_score(Request $request)
    {
        $word = $request->query->get('term');
        $db_reader = new DatabaseReader();
        $repository = $this->getDoctrine()->getRepository(Words::class);
        $res = $db_reader->search_word_db($word, $repository);

        if (!$res) {
            $gitHubSearcher = new GitHubSearcher();

            $positive = $gitHubSearcher->exec_gitHub_req($word, "rocks");
            $negative = $gitHubSearcher->exec_gitHub_req($word, "sucks");
            $request = array("word" => $word, "positive" => $positive, "negative" => $negative);
            $gitHubSearcher->insert_word($request, $this->entityManager);


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

    private function calculate_score($positive, $total) :float {
        $score = (float)($positive / ($total) *10);
        return $score;

    }

    private function write_output($total, $word, $score) : JsonResponse
    {
        if ($total) {
            return JsonResponse::create(array('term' => $word, 'score' => $score));
        } else {
            return JsonResponse::create(array('message' => 'No results for word ' . $word));
        }
    }
}

class DatabaseReader extends AbstractController
{

    public function search_word_db($word, $repository)
    {
        $res = $repository->findOneBy(['word' => $word]);
        return $res;
    }

}

class GitHubSearcher extends AbstractController
{

    public function exec_gitHub_req($word, $q_add) : int
    {
        $url = "https://api.github.com/search/issues?q=" . $word . "+q=" . $q_add;
        $headers = ['User-Agent: ipavelic'];

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_HEADER, 0); //use this to suppress output
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($handle);
        curl_close($handle);

        $res = json_decode($data, true);
        return $res["total_count"];
    }

    public function insert_word($request, EntityManagerInterface $em)
    {
        $word = new Words();
        $word->setWord($request["word"]);
        $word->setPositive($request["positive"]);
        $word->setNegative($request["negative"]);
        $word->setSource(1);

        $em->persist($word);
        $em->flush();
    }

}