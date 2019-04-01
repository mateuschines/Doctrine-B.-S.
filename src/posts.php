<?php 

use App\Entity\Post;
use App\Entity\Category;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;


$map->get('posts.list', '/posts', function ( $request, $response) use ($view, $entityManager) {
    $postRepository = $entityManager->getRepository(Post::class);
    $posts = $postRepository->findAll();
    return $view->render($response, 'posts/list.phtml', [
        'posts' => $posts
    ]);

});

$map->get('posts.create', '/posts/create', function ( $request, $response) use ($view) {
    
    return $view->render($response, 'posts/create.phtml');

});

$map->post('posts.store', '/posts/store',
     function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
    //pegar os dados do envio
    $data = $request->getParsedBody();
    //entity naom gerenciada
    $post = new Post();
    $post->setTitle($data['title'])
        ->setContent($data['content']);
    //persist muda o estado da entidade
    $entityManager->persist($post);//so conhecendo a entidade managed
    //esse cara propaga tudo no banco pode inserir alterar apagar
    $entityManager->flush();
    $uri = $generator->generate('posts.list');
    return new Response\RedirectResponse($uri);

});

$map->get('posts.edit', '/posts/{id}/edit', function (ServerRequestInterface $request, $response) use ($view, $entityManager) {

    $id = $request->getAttribute('id');
    $postRepository = $entityManager->getRepository(Post::class);
    $post = $postRepository->find($id);//ira retornar a consulyta por id
    return $view->render($response, 'posts/edit.phtml', [
        'post' => $post
    ]);

});

$map->post('posts.update', '/posts/{id}/update',
     function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
    $id = $request->getAttribute('id');    
    $postRepository = $entityManager->getRepository(Post::class);
    $post = $postRepository->find($id);

    //pegar os dados do envio
    $data = $request->getParsedBody();
    
    $post->setTitle($data['title'])
        ->setContent($data['content']);

    //esse cara propaga tudo no banco pode inserir alterar apagar
    $entityManager->flush();
    $uri = $generator->generate('posts.list');
    return new Response\RedirectResponse($uri);

});

$map->get('posts.remove', '/posts/{id}/remove',
     function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
    $id = $request->getAttribute('id');    
    $postRepository = $entityManager->getRepository(Post::class);
    $post = $postRepository->find($id);

    $entityManager->remove($post);
    //esse cara propaga tudo no banco pode inserir alterar apagar
    $entityManager->flush();
    $uri = $generator->generate('posts.list');
    return new Response\RedirectResponse($uri);

});

$map->get('posts.categories', '/posts/{id}/categories',
    function (ServerRequestInterface $request, $response) use ($view, $entityManager) {
        $id = $request->getAttribute('id');
        $repository = $entityManager->getRepository(Post::class);
        $categoryPostRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryPostRepository->findAll();
        $post = $repository->find($id);
        return $view->render($response, 'posts/categories.phtml', [
            'post' => $post,
            'categories' => $categories
        ]);
    });

    
$map->post('posts.set-categories', '/posts/{id}/set-categories',
    function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        
        $repository = $entityManager->getRepository(Post::class);
        $categoryRepository = $entityManager->getRepository(Category::class);
        
        
        /** @var Post $post */
        $post = $repository->find($id);
        //limpar o array colection limpe de uma vez por todas o post
        $post->getCategories()->clear();//limpa nosso arrayColection
        $entityManager->flush();//reflete isso no banco de dados
        
        foreach ($data['categories'] as $idCategory){
            $category = $categoryRepository->find($idCategory);
            $post->addCategory($category);
        }
        $entityManager->flush();
        $uri = $generator->generate('posts.list');
        return new Response\RedirectResponse($uri);
    });