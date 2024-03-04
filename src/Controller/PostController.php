<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Entity\PostReactions;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostReactionsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $posts = $postRepository->findAll();

    $tags = [];
    foreach ($posts as $post) {
        $tags = array_merge($tags, explode(',', $post->getTag()));
    }
    $tags = array_unique($tags);
    
    $pagination = $paginator->paginate(
        $posts,
        $request->query->getInt('page', 1), // Get the current page number from the request, default to 1
        10 // Items per page
    );

    return $this->render('post/index.html.twig', [
        'posts' => $posts,
        'tags' => $tags,
        'pagination' => $pagination,
    ]);
}

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $postReaction = new PostReactions(0,0);
        $post->setPostReactions($postReaction);
        $post->setDate(new \DateTime());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            // Handle file upload
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the uploads directory
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if necessary
                }
    
                // Set the image property of the post entity to the filename
                $post->setImage($newFilename);
                $contenu = $form->get('contenu')->getData();

                // Check for bad words
                $badWords = ["null", "banal", "sexe", "attaque", "blessure", "merde", "salope"];
                foreach ($badWords as $badWord) {
                    if (stripos($contenu, $badWord) !== false) {
                        $this->addFlash('error', 'Veuillez supprimer les mots inappropriés de votre commentaire.');
                        return $this->renderForm('post/new.html.twig', [
                            'post' => $post,
                            'form' => $form,
                        ]);
                    }
                }
            }

            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire a été soumis avec succès.');

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    
    }


    #[Route('/show/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
    public function show(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comments=$post->getComment();
        $comment->setDate(new \DateTime());
        $comment->setTime(new \DateTime());


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $comment->setPost($post);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            
          

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);

        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comment'=>$comment,
            'form' => $form->createView(),
            'comments'=>$comments,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            // Handle file upload
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the uploads directory
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if necessary
                }
    
                // Set the image property of the post entity to the filename
                $post->setImage($newFilename);
            }
            $entityManager->flush();

            
            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_post_delete',methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
       
        $comments = $post->getComment();
        foreach ($comments as $comment) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }
    
     
    
            // Now delete the post itself
            $entityManager->remove($post);
            $entityManager->flush();
    
            // Add success flash message
          
        
    
        return $this->redirectToRoute('app_post_index');
    }
        
    #[Route('/increment_likes/{id}', name: 'likes',methods: ['POST'])]
     
    public function incrementLikesAction(EntityManagerInterface $entityManager,int $id,Request $request): Response
    {
     
      $post= $entityManager->getRepository(Post::class)->find($id);
      $postRId= $post->getPostReactions();
      $postRId->setLikes($postRId->getLikes()+1);
      //$postRId->setDislike($postRId->getDislike()-1);
      $entityManager->flush();
            return $this->redirectToRoute('app_post_show',  ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/Deccrement_likes/{id}', name: 'Dislikes',methods: ['POST'])]
     
    public function deccrementLikesAction(EntityManagerInterface $entityManager,int $id,Request $request): Response
    {
     
      $post= $entityManager->getRepository(Post::class)->find($id);
      $postRId= $post->getPostReactions();
      $postRId->setDislike($postRId->getDislike()+1);
    //  $postRId->setLikes($postRId->getLikes()-1);
      $entityManager->flush();
            return $this->redirectToRoute('app_post_show',  ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/tags/{tag}', name: 'tags',methods: ['GET'])]

    public function showByTag($tag,PostRepository $postRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $posts = $postRepository->findAll();
        $tags = [];
        foreach ($posts as $post) {
            $tags = array_merge($tags, explode(',', $post->getTag()));
        }
        $tags = array_unique($tags);
        $posts = $postRepository->findByTag($tag);
        $pagination = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1), // Get the current page number from the request, default to 1
            10 // Items per page
        );
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'tags' =>$tags,
            'pagination' => $pagination,

        ]);


        }
        
    /**
     * @Route("/search", name="post_search", methods={"GET"})
     *//*
    #[Route('/search', name: 'app_rechercher_post',methods: ['GET'])]
    public function search(Request $request, PostRepository $postRepository): Response
    {
        echo "hellp";
        $query = $request->query->get('input');
        $tags = [];
        
        $posts = $postRepository->findBySearchQuery($query);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'tags' =>$tags
        ]);

        }
        */

        #[Route('/search', name: 'search_posts', methods: ['GET'])]    
        public function search(Request $request, PostRepository $postRepository): Response
        {
            $query = $request->query->get('search');
            
            // Echo the search query for testing
        
            // Search for posts based on the query
            $posts = $postRepository->findBySearchQuery($query);
           
            $postsArray = [];
            $posts = $postRepository->findBySearchQuery($query);
            $postsFound = false;

           /* // Check if any posts are fetched
            if ($posts) {
                // Iterate over the fetched posts and store them in an array
                foreach ($posts as $post) {
                    $postsArray[] = $post;
                }
            
                // Set the flag to true indicating posts are found
                $postsFound = true;
            }
            
            // Check if posts are found
            if ($postsFound) {
                echo 'Posts found';
            } else {
                echo 'No posts found';
            }
         
                        if (empty($postsArray) && !empty($query)) {
                // Echo a message for testing
                echo 'No posts found. Searching for similar posts...<br>';
        
                // Find similar posts using Levenshtein distance
                $similarPosts = $this->findSimilarPosts($query, $postRepository->findAll());
                
                // Echo the number of similar posts found for testing
                echo 'Number of similar posts found: ' . count($similarPosts) . '<br>';
                
                // Return the response with the similar posts
                return $this->render('post/search_results.html.twig', [
                    'posts' => $similarPosts,
                ]);
            }
        
            // Echo the number of posts found for testing
            echo 'Number of posts found: ' . count($posts) . '<br>';
            */
            // Return the response with the found posts
            return $this->render('post/search_results.html.twig', [
                'posts' => $posts,
            ]);
        }
        
        #[Route('/verify', name: 'search_post', methods: ['GET'])]
        public function verify(Request $request, PostRepository $postRepository,PaginatorInterface $paginator): Response
        {
            $query = $request->query->get('search');
            
            $posts = $postRepository->findAll();
    
            $searchResults = [];
    
            $threshold = 3;
    
            foreach ($posts as $post) {
                $distance = levenshtein($query, $post->getName());
    
                if ($distance <= $threshold) {
                    $searchResults[] = $post;
                }
            }
            $tags = [];
            foreach ($posts as $post) {
                $tags = array_merge($tags, explode(',', $post->getTag()));
            }
            $tags = array_unique($tags);
            
            $pagination = $paginator->paginate(
                $searchResults,
                $request->query->getInt('page', 1), // Get the current page number from the request, default to 1
                3 // Items per page
            );
        
            return $this->render('post/index.html.twig', [
                'posts' => $searchResults,
                'tags' => $tags,
                'pagination' => $pagination,
            ]);
            
          
        }
    

        private function findSimilarPosts(string $query, array $posts): array
        {
            $similarPosts = [];
            foreach ($posts as $post) {
                $postTitle = $post->getTitle(); // Change this to whatever attribute you want to compare
                
                $similarity = $this->calculateSimilarity($query, $postTitle);
                
                // Adjust the threshold as needed
                if ($similarity >= 0.7) {
                    $similarPosts[] = $post;
                }
            }
            
            return $similarPosts;
        }
        
        private function calculateSimilarity(string $query, string $string): float
        {
            $maxLen = max(strlen($query), strlen($string));
            if ($maxLen === 0) {
                return 1.0;
            }
            
            $distance = levenshtein($query, $string);
            return 1.0 - ($distance / $maxLen);
        }
        #[Route('/tree', name: 'tree_post', methods: ['GET'])]    
        public function tree(Request $request,PaginatorInterface $paginator, PostRepository $postRepository, PostReactionsRepository $postReactionsRepository): Response
        {
            $posts = $postRepository->findAllWithReactions();
            $tags = $this->fetchTags($posts);
            
            // Sort posts by the number of likes
            usort($posts, function($a, $b) {
                return $b->getPostReactions()->getLikes() - $a->getPostReactions()->getLikes();
            });
            $pagination = $paginator->paginate(
                $posts,
                $request->query->getInt('page', 1), // Get the current page number from the request, default to 1
                10 // Items per page
            );
            return $this->render('post/index.html.twig', [
                'posts' => $posts,
                'tags' => $tags,
                'pagination' => $pagination,

            ]);
        }
    
        #[Route('/tree_date', name: 'tree_postByDate', methods: ['GET'])]    
        public function treeByDate(Request $request,PaginatorInterface $paginator, PostRepository $postRepository, PostReactionsRepository $postReactionsRepository): Response
        {
            $posts = $postRepository->findAllWithReactions();
            $tags = $this->fetchTags($posts);
            
                     usort($posts, function($a, $b) {
                $dateA = $a->getDate();
                $dateB = $b->getDate();
                return $dateB <=> $dateA;
            });
            $pagination = $paginator->paginate(
                $posts,
                $request->query->getInt('page', 1), // Get the current page number from the request, default to 1
                10 // Items per page
            );  
            
            return $this->render('post/index.html.twig', [
                'posts' => $posts,
                'tags' => $tags,
                'pagination' => $pagination,

            ]);
        }
    
        private function fetchTags(array $posts): array
        {
            $tags = [];
            foreach ($posts as $post) {
                $tags = array_merge($tags, explode(',', $post->getTag()));
            }
            return array_unique($tags);
        }    /*
public function searchPosts(Request $request)
{
    // Fetch search query from request
    $searchQuery = $request->query->get('search');

    // Logic to search posts based on the name attribute
    // Replace this with your actual logic to fetch search results from the database

    // For example, assuming $searchResults is an array of posts
    $searchResults = $this->getDoctrine()->getRepository(Post::class)->findBy(['name' => $searchQuery]);

    // Render search results using a Twig template
    $html = $this->renderView('search_results.html.twig', ['searchResults' => $searchResults]);

    // Return HTML response
    return new Response($html);
}
       */ 

}
