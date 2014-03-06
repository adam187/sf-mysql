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
    public function categoryAction($slug)
    {
        $categoryRepo = $this->getDoctrine()->getRepository('Entity:Category');
        $productRepo  = $this->getDoctrine()->getRepository('Entity:Product');

        $category = $categoryRepo->findOneBySlug($slug);


        if ($category->isLeaf()) {
            $products = $productRepo->getProductsFromCategory($category, rand(0, 100));
            if (!$products) {
                $products = $productRepo->getProductsFromCategory($category);
            }
        } else {
            $leafs = $categoryRepo->getAllLeavsIds($category);
            $products = $productRepo->getProductsFromCategories($leafs);
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
