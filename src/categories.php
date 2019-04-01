<?php 

use App\Entity\Category;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;


$map->get('categories.list', '/categories', function ( $request, $response) use ($view, $entityManager) {
    $categoryRepository = $entityManager->getRepository(Category::class);
    $categories = $categoryRepository->findAll();
    return $view->render($response, 'categories/list.phtml', [
        'categories' => $categories
    ]);

});

$map->get('categories.create', '/categories/create', function ( $request, $response) use ($view) {
    
    return $view->render($response, 'categories/create.phtml');

});

$map->post('categories.store', '/categories/store',
     function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
    //pegar os dados do envio
    $data = $request->getParsedBody();
    //entity naom gerenciada
    $category = new Category();
    $category->setName($data['name']);
    //persist muda o estado da entidade
    $entityManager->persist($category);//so conhecendo a entidade managed
    //esse cara propaga tudo no banco pode inserir alterar apagar
    $entityManager->flush();
    $uri = $generator->generate('categories.list');
    return new Response\RedirectResponse($uri);

});

$map->get('categories.edit', '/categories/{id}/edit', function (ServerRequestInterface $request, $response) use ($view, $entityManager) {
    
    $id = $request->getAttribute('id');
    $categoryRepository = $entityManager->getRepository(Category::class);
    $category = $categoryRepository->find($id);//ira retornar a consulyta por id
    return $view->render($response, 'categories/edit.phtml', [
        'category' => $category
    ]);

});

$map->post('categories.update', '/categories/{id}/update',
     function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
    $id = $request->getAttribute('id');    
    $categoryRepository = $entityManager->getRepository(Category::class);
    $category = $categoryRepository->find($id);

    //pegar os dados do envio
    $data = $request->getParsedBody();
    
    $category->setName($data['name']);

    //esse cara propaga tudo no banco pode inserir alterar apagar
    $entityManager->flush();
    $uri = $generator->generate('categories.list');
    return new Response\RedirectResponse($uri);

});

$map->get('categories.remove', '/categories/{id}/remove',
     function (ServerRequestInterface $request, $response) use ($view, $entityManager, $generator) {
    $id = $request->getAttribute('id');    
    $categoryRepository = $entityManager->getRepository(Category::class);
    $category = $categoryRepository->find($id);

    $entityManager->remove($category);
    //esse cara propaga tudo no banco pode inserir alterar apagar
    $entityManager->flush();
    $uri = $generator->generate('categories.list');
    return new Response\RedirectResponse($uri);

});