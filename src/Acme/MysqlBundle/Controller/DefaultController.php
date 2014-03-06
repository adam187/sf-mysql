<?php

namespace Acme\MysqlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Acme\MysqlBundle\Entity\Category;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function menuAction()
    {
        $repo = $this->getDoctrine()->getRepository('Entity:Category');
        $rootCategory = $repo->findOneBy(array('parent' => null, 'root' => 1));

        return array(
            'categories' => $rootCategory->getChildren(),
        );
    }

    /**
     * @Template()
     */
    public function betterMenuAction()
    {
        $repo = $this->getDoctrine()->getRepository('Entity:Category');
        $categories = $repo->findAllInOneQueryByRoot();

        return array(
            'categories' => $categories,
        );
    }

    /**
     * @Route("/category/{slug}", name="category")
     * @Template()
     */
    public function categoryAction(Request $request, $slug)
    {
        $categoryRepo = $this->getDoctrine()->getRepository('Entity:Category');
        $productRepo  = $this->getDoctrine()->getRepository('Entity:Product');

        $category = $categoryRepo->findOneBySlug($slug);

        if ($category->isLeaf()) {
            $query = $productRepo->getProductsFromCategoryQuery($category);
        } else {
            $leafs = $categoryRepo->getAllLeavsIds($category);
            $query = $productRepo->getProductsFromCategoriesQuery($leafs);
        }

        $paginator  = $this->get('knp_paginator');
        $products = $paginator->paginate($query, $request->query->get('page', 1), 10, array('distinct' => true));

        return array(
            'products' => $products,
        );
    }

    /**
     * @Route("/product/{slug}", name="product")
     * @Template()
     */
    public function productAction($slug)
    {
        $product = $this->getDoctrine()->getRepository('Entity:Product')->findOneBySlug($slug);

        return array(
            'product' => $product,
        );
    }

    /**
     * @Route("/load", name="load")
     * @Template()
     */
    public function loadAction()
    {
        $products = $this->getDoctrine()->getRepository('Entity:Product')->findRandomProducts(rand(0, 1000));

        return array('products' => $products);
    }

}
