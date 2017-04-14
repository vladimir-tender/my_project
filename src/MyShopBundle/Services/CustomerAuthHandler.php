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

    /**
     * @var \Swift_Mailer $swiftmailer
     */
    private $swiftMailer;


    public function __construct($em, $validator, $passEncode, $swiftMailer)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->passEncode = $passEncode;
        $this->swiftMailer = $swiftMailer;

    }

    public function customerActionHandler(Request $request, Form\Form $form, Customer $customer)
    {
        if ($request->isMethod("POST")) {
            /**
             * @var Form\Form $form
             */
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                if ($this->checkCustomerExists($customer) === true) {
                    return 'customerExists';
                }

                $validator = $this->validator;
                $errors = $validator->validate($customer);
                $formValid = $form->isValid();

                if (count($errors) > 0 || $formValid === false) {
                    return $form;
                } else {

                    $encoder = $this->passEncode;
                    $password_hashed = $encoder->encodePassword($customer, $customer->getPassword());
                    $customer->setPassword($password_hashed);
                    $customer->setStatus(Customer::USER_INACTIVE);

                    $hash = $this->confirmEmail($customer, $request->getSchemeAndHttpHost());

                    $customer->setHash($hash);

                    $em = $this->em;
                    $em->persist($customer);
                    try {
                        $em->flush();
                        return true;
                    } catch (\Exception $exception) {
                        throw new \Exception("Failed to insert INTO DB. Maybe double.");
                    }

                }
            }
        }
    }


    public function confirmEmail($customer, $host)
    {
        /**
         * @var Customer $customer
         */
        $hash = sha1($customer->getEmail() . time());
        $url = $host . "/customer/confirm/" . $hash;

        $text = "Dear user! <br> 
                Thank you for registering at our shop!<br>
                Before we can activate your account one last step must be taken to complete your registration.<br>
                Please note - you must complete this last step to become a registered member. You will only need to visit this URL once to activate your account.
                To complete your registration, please visit this URL: <br>
                <a href='" . $url . "'>Click here</a>";

        $message = \Swift_Message::newInstance();
        $message
            ->setSubject('Confirm user Email')
            ->setFrom('igorstokolos@gmail.com')
            ->setTo($customer->getEmail())
            ->setBody($text, 'text/html');

        try {
            /**@var \Swift_Message $message */
            $this->swiftMailer->send($message);
        } catch (\Exception $exception) {
            $hash = false;
        }

        return $hash;
    }


    public function checkCustomerExists($customer)
    {
        /**
         * @var Customer $customer
         */
        $customerExists = $this->em->getRepository("MyShopBundle:Customer")->findOneBy(['email' => $customer->getEmail()]);

        if ($customerExists == null) {
            return false;
        } else {
            return true;
        }
    }

    public function confirmEmailHash($hash)
    {
        $customer = $this->em->getRepository("MyShopBundle:Customer")->findOneBy(["hash" => $hash]);
        if ($customer != null) {
            $customer->setStatus(Customer::USER_ACTIVE);
            $this->em->persist($customer);
            try {
                $this->em->flush();
                return true;
            } catch (\Exception $exception) {
                return false;
            }
        }
        else {
            return false;
        }
    }


}