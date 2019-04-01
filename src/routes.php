<?php

use App\Entity\Category;
use App\Entity\Post;
use Aura\Router\RouterContainer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

//container de rotas do aura router
$routerContainer = new RouterContainer();

//para gerar uri
$generator = $routerContainer->getGenerator();

//gerar mapeamento das rotas
$map = $routerContainer->getMap();

//passar o caminho dos templates
$view = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');

//pegando do doctrine .php para fazer consulta dos dados
$entityManager = getEntityManager();

//as rotas, nome da rota
$map->get('home', '/', function (ServerRequestInterface $request, $response) use ($view, $entityManager) {

     $postsRepository = $entityManager->getRepository(Post::class);
     
     $categoryRepository = $entityManager->getRepository(Category::class);
     
     $categories = $categoryRepository->findAll();
     
     $data = $request->getQueryParams();//parametros por get
     
     //a pesquisa estiver embranco e estiver vazio
     if(isset($data['search']) and $data['search']!=""){
         
        //montar query OOb
        $queryBuilder = $postsRepository->createQueryBuilder('p');
         
        $queryBuilder->join('p.categories', 'c')
             ->where($queryBuilder->expr()->eq('c.id', $data['search']));
         
             $posts = $queryBuilder->getQuery()->getResult();
     }else{
         $posts = $postsRepository->findAll();
     }
     return $view->render($response, 'home.phtml', [
         'posts' => $posts,
         'categories' => $categories
     ]);
});

require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/posts.php';

//combinação
$matcher = $routerContainer->getMatcher();

//combinando aura router cokm as requisicao e com as rotas
$route = $matcher->match($request);

//pegar os atributos da rotas e passar na requisicao
foreach ($route->attributes as $key => $value) {
//nossa requisicao sera iguala  de cima com o metood da ps7 varios atributos     
    $request = $request->withAttribute($key, $value);
}

//handle tem uma funcao que o call ira rceber
$callable = $route->handler;


/** @var Response $response */
$response = $callable($request, new Response());



 if ($response instanceof Response\RedirectResponse) {
     //retorna a uri o location é a uri
     header("location:{$response->getHeader("location")[0]}");
 } elseif ($response instanceof Response) {
     //pega o corpo tranforma em str e retorna pro usuario
     echo $response->getBody();
 }