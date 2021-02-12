<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EmailType;
use App\Form\getEmailType;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelper;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/mdp-oublie", name="mdp-oublie")
     */
    public function recupMdp(
        Request $request,
        UserRepository $userRepo,
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $em
    ) {
        //return new Response('<html><body><h2>je suis là</h2></body></html>');
        $emailForm = $this->createForm(getEmailType::class);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {

            $email = $emailForm->getData()['email'];
            $donnee = $userRepo->checkEmail($email);

            if (!$donnee) {
                $this->addFlash('fail', "Cette adresse n'existe pas");
            } else {
                $signatureComponents = $helper->generateSignature("app_login", $donnee[0]->getId(), $donnee[0]->getEmail());
                //$random = random_bytes(10);

                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $length = 10;

                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                $password = $passwordEncoder->encodePassword($donnee[0], $randomString);
                $donnee[0]->setPassword($password);

                $em->flush();

                $template = (new TemplatedEmail())
                    ->from(new Address('vintage.mapro@gmail.com', '"Mapro compte"'))
                    ->to($email)
                    ->subject('Mot de passe oublié')
                    ->htmlTemplate('security/mdp_oublie.html.twig');

                $context = $template->getContext();
                $context['signedUrl'] = $signatureComponents->getSignedUrl();
                $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
                $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
                $context['passWord'] = $randomString;

                $template->context($context);
                $mailer->send($template);

                $this->addFlash('success', 'Un email vous a été envoyé');
            }
        }
        return $this->render("security/change_pw.html.twig", [
            'emailForm' => $emailForm->createView()
        ]);
    }
}
