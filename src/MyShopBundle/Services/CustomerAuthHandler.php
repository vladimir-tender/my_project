<?php
/**
 * Created by PhpStorm.
 * User: hysteria
 * Date: 10.04.17
 * Time: 20:28
 */

namespace MyShopBundle\Services;


use Doctrine\ORM\EntityManager;
use MyShopBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use MyShopBundle\Form\CustomerType;
use Symfony\Component\Form;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerAuthHandler
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var ValidatorInterface $validator
     */
    private $validator;

    /**
     * @var PasswordEncoderInterface $passEncode
     */
    private $passEncode;

    public function __construct($em, $validator, $passEncode)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->passEncode = $passEncode;
    }

    public function customerActionHandler(Request $request, Form\Form $form, Customer $customer)
    {
        if ($request->isMethod("POST")) {
            /**
             * @var Form\Form $form
             */
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($request->get("reg") == 1) {
                    //var_dump("reg");
                    $errors = $this->validator->validate($customer);
                    //var_dump($errors);
                    if (count($errors) > 0) {
                        return $form;
                    } else {

                        $encoder = $this->passEncode;
                        $password_hashed = $encoder->encodePassword($customer, $customer->getPassword());
                        $customer->setPassword($password_hashed);

                        $em = $this->em;
                        $em->persist($customer);
                        $em->flush();

                        return true;
                    }
                } else {
                    //var_dump("login");
                }
            }
        }

    }
}