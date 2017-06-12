<?php

namespace SoftUniBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\Routing\Annotation\Route;


/**
 * Class NotifyController
 * @package SoftUniBundle\Controller
 * @author Plamen Markov <plamen@lynxlake.org>
 *
 * @Route("admin/notify")
 */
class NotifyController extends Controller
{
    private $emails = [
        'plamen326@gmail.com',
        'plamen@lynxlake.org'
    ];

    /**
     * @Route("/", name="admin_notify_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createNotifyForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // parse input
            $emails = array_unique(array_map('trim', explode(',', $form->getData()['emails'])));
            if (empty($emails)) {
                $this->addFlash('danger', 'E-mails are missing.');
                return $this->redirectToRoute('admin_notify_index');
            }

            $subject = 'Notification For New Products';
            $template = $this->render('SoftUniBundle:notify:email.html.twig');

            $manager = $this->get('softuni.notify_manager');
            $cnt = $manager->sendEmails($emails, $subject, $template);

            $this->addFlash('success', 'New product notifications successfully submitted to ' . $cnt . ' recipient(s).');

            return $this->redirectToRoute('admin_notify_index');
        }

        return $this->render('SoftUniBundle:notify:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Creates a form with predefined email addresses to notify for new products.
     **
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNotifyForm()
    {
        return $this->createFormBuilder(['emails' => implode(', ', $this->emails)])
            ->add('emails', TextareaType::class, ['label' => 'E-mails', 'attr' => ['placeholder' => 'E-mail Addresses...', 'required' => true]])
            ->getForm();
    }
}
