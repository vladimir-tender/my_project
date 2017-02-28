<?php

namespace AdminBundle\Controller;


use MyShopBundle\Entity\User;
use MyShopBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
    /** @Template */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository("MyShopBundle:User")->findAll();
        return [
            'users' => $users
        ];
    }

    /**
     * @Template()
     */
    public function addAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            $clearPassword = $form->get("clearPassword")->getData();

            $password = $this->get("security.password_encoder")->encodePassword($user, $clearPassword);
            $user->setPassword($password);


            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("admin.users");
        }

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Template()
     */
    public function editAction(Request $request,$id)
    {
        $user = $this->getDoctrine()->getRepository("MyShopBundle:User")->find($id);

        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            $clearPassword = $form->get("clearPassword")->getData();

            $password = $this->get("security.password_encoder")->encodePassword($user, $clearPassword);
            $user->setPassword($password);


            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            ///mailReport
            $mailer = $this->get("admin.actions_mailer");
            $message_body = "Данные пользователя #" . $user->getId() . " \"" . $user->getUsername() . "\" изменены. ";
            try {
                $mailer->sendReportUserAction($message_body, $this->getUser());
                $this->addFlash("success", "Данные пользователя изменены.");
            } catch (\Exception $exception) {
                $this->addFlash("failed", "Ошибка отправки письма." . $exception);
            }
            ///mailReport

            return $this->redirectToRoute("admin.users");
        }

        return [
            "form" => $form->createView(),
            "id" => $id
        ];
    }


}

