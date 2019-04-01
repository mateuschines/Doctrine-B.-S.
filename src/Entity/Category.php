<?php

namespace App\Entity;

//mapear a class
//essa classe sea uma entidade
//mapeamento com nosso bd relacional

/**
 * @Entity
 * @Table(name="categories")
 */


class Category 
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
    private $nome;

    
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
    public function getName()
    {
        return $this->nome;
    }

    /**
     * @param mixed $name
     *
     * @return  self
     */ 
    public function setName($nome)
    {
        $this->nome = $nome;

        return $this;
    }
    //entidade é so representacao, nao faz nada alen disso
    //modelo ja pode representar e fazer alteraçoes
    //ultiliza um framework full estacmvc que tem o model
    //ultiliza active records esse modelo permite que vc faz alteracoes

}