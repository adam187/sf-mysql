<?php

namespace Acme\MysqlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        $rootCategory = $repo->findOneBy(array('parent' => null));

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
    public function categoryAction($slug)
    {
        $category = $this->getDoctrine()->getRepository('Entity:Category')->findOneBySlug($slug);

        $repo = $this->getDoctrine()->getRepository('Entity:Product');

        $products = $repo->getProductsFromCategory($category, rand(0, 100));
        if (!$products) {
            $products = $repo->getProductsFromCategory($category);
        }

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

}
