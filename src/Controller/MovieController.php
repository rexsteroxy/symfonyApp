<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Movie;
use App\Form\MovieType;
/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class MovieController extends FOSRestController
{
  /**
   * Lists all Movies.
   * @Rest\Get("/movies")
   *
   * @return Response
   */
  public function getMovies()
  {
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $movies = $repository->findall();
    return $this->handleView($this->view($movies));
  }
  /**
   * Create Movie.
   * @Rest\Post("/movie")
   *
   * @return Response
   */
  public function createMovies(Request $request)
  {
    $movie = new Movie();
    $form = $this->createForm(MovieType::class, $movie);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($movie);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }



     /**
   * Delete Movie.
   * @Rest\Delete("/delete/{id}")
   *
   * @return Response
   */
    public function deleteMovies(Movie $movie)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($movie);
        $entityManager->flush();
        return $this->handleView($this->view(null));
    }


      /**
   * Update Movie.
   * @Rest\Patch("/update/{id}")
   *
   * @return Response
   */
  public function updateMovies(Movie $movie)
  {

    $movie = $this->buildForm(MovieType::class, $movie,[

      'method' => $request->getMethod(),
      
      ]);
      
      
      $form->handleRequest($request);
      
      if(!$form->isSubmitted() || !$form->isValid()){
      return $this->respond($form, statusCode,
      Response::HTTP_BAD_REQUEST);
      }
      
      $movieData = $form->getData();



      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($movieData);
      $entityManager->flush();
      return $this->handleView($this->view($movieData));
  }
}