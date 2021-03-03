<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CredentialsController extends AbstractController
{
    /**
     * @Route("/credits", name="credentials_credits")
     */
    public function credits(): Response
    {
        return $this->render('credentials/credits.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="credentials_legals")
     */
    public function legals(): Response
    {
        return $this->render('credentials/legals.html.twig');
    }
}
