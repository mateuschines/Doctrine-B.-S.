<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
//mapear a class
//essa classe sea uma entidade
//mapeamento com nosso bd relacional

/**
 * @Entity
 * @Table(name="posts")
 */


class Post 
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string", length=100)
     */
    private $title;

    /**
     * @Column(type="text")
     */
    private $content;

    /**
     * @ManyToMany(targetEntity="App\Entity\Category")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    
//o @ é o doc block
//anotations crachas personalizados que geram mapeamentos
 

    /**
     * @return mixed
     */ 
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
    //entidade é so representacao, nao faz nada alen disso
    //modelo ja pode representar e fazer alteraçoes
    //ultiliza um framework full estacmvc que tem o model
    //ultiliza active records esse modelo permite que vc faz alteracoes


    /**
     * @return mixed
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function addCategory(Category $category){
        $this->categories->add($category);
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getCategories(){
        return $this->categories;
    }
}