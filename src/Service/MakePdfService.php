<?php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\Mime\Email;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MakePdfService extends AbstractController
{
    private $reservationRepository;
    private $mailer;


    public function __construct(ReservationRepository $reservationRepository, MailerInterface $mailerInterface)
    {
        $this->reservationRepository = $reservationRepository;
        $this->mailer = $mailerInterface;
    }
    /**
     * @Return bon de reservation pdf
     */
    // public function makeBilletPdf($reservation, $company, $driver, $car, $client, $total, $fileName, $userEmail)
    // {
    //     if ($reservation) {


    //         ob_start();

    //         //require_once '../templates/make_pdf/pdf_billet.html.php';
    //         require_once '../templates/make_pdf/pdf_billet.html.php';

    //         $html = ob_get_contents();

    //         ob_end_clean();



    //         // instantiate and use the dompdf class
    //         $dompdf = new Dompdf();

    //         $dompdf->loadHtml($html);


    //         // (Optional) Setup the paper size and orientation
    //         $dompdf->setPaper('A4');

    //         // Render the HTML as PDF
    //         $dompdf->render();


    //         // on envoi par mail le pdf
    //         $output = $dompdf->output();

    //         //on enregistre le fichier sur le serveur
    //         file_put_contents($fileName, $output);

    //         $email = (new Email())
    //             ->from('booking@paris-prestige-transfert.fr')
    //             ->to($userEmail)
    //             ->attachFromPath($fileName)
    //             ->subject('Vous avez une nouvelle reservation!!')
    //             ->text('Sending emails is fun again!')
    //             ->html('<p>Vous avez une nouvelle reservation!</p>');

    //         $this->mailer->send($email);
    //         //on supprime le fichier du serveur
    //         unlink($fileName);



    //         // Output the generated PDF to Browser with document name("RESERVATION-01-12-1979")
    //         $dompdf->stream("RESERVATION-" . date_format($reservation->getOperationAt(), "d-m-Y"));
    //     }
    // }

    /**
     * @Return facture pdf
     */
    public function makeFacturePdf($invoice, $user, $bankAccount): void
    {

        if ($invoice) {

            // ob_start();

            // require_once '../templates/make_pdf/pdf_facture.html.php';

            // $html = ob_get_contents();

            // ob_end_clean();
            $html = $this->render('pdf/invoice.html.twig', [
                'invoice' => $invoice,
                'company' => $user->getCompagny(),
                'setting' => $user->getSetting(),
                'bankAccount' => $bankAccount

            ]);

            // instantiate and use the dompdf class
            $dompdf = new Dompdf();

            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4');

            // Render the HTML as PDF
            $dompdf->render();

            // on envoi par mail le pdf
            $output = $dompdf->output();

            //on enregistre le fichier sur le serveur
            file_put_contents($fileName, $output);

            // $email = (new Email())
            //     ->from('booking@paris-prestige-transfert.fr')
            //     ->to($userEmail)
            //     ->attachFromPath($fileName)
            //     ->subject('Facture')
            //     ->text('Sending emails is fun again!')
            //     ->html('<p>Voici la facture que vous avez demander</p>');

            // $this->mailer->send($email);
            // //on supprime le fichier du serveur
            // unlink($fileName);


            // Output the generated PDF to Browser with document name("RESERVATION-01-12-1979")
            $dompdf->stream("FACTURE-" . $client->getName() . "-" . date_format($reservation->getCreatAt(), "d-m-Y"));
        }
    }

    /**
     * @Return facture pdf
     */
    // public function makeFactureLibrePdf($facture, $company, $totalArray, $bank, $setting, $fileName, $userEmail): void
    // {

    //     if ($facture) {

    //         ob_start();

    //         require_once '../templates/make_pdf/pdf_facture_libre.html.php';

    //         $html = ob_get_contents();

    //         ob_end_clean();

    //         // instantiate and use the dompdf class
    //         $dompdf = new Dompdf();

    //         $dompdf->loadHtml($html);

    //         // (Optional) Setup the paper size and orientation
    //         $dompdf->setPaper('A4');

    //         // Render the HTML as PDF
    //         $dompdf->render();


    //         // Output the generated PDF to Browser with document name("RESERVATION-01-12-1979")
    //         $dompdf->stream("FACTURE-" . $facture->getName() . "-" . date_format($facture->getDate(), "d-m-Y"));
    //     }
    // }

    // /**
    //  * @Return facture pdf
    //  */
    // public function makeListeCommandeDispatcherPdf($resultats, $dispatcher, $company, $totalArray, $setting, $fileName, $userEmail, $date_star, $date_end): void
    // {

    //     if ($resultats) {

    //         ob_start();

    //         require_once '../templates/make_pdf/pdf_liste_commande_dispatcher.html.php';

    //         $html = ob_get_contents();

    //         ob_end_clean();

    //         // instantiate and use the dompdf class
    //         $dompdf = new Dompdf();

    //         $dompdf->loadHtml($html);

    //         // (Optional) Setup the paper size and orientation
    //         $dompdf->setPaper('A4');

    //         // Render the HTML as PDF
    //         $dompdf->render();


    //         // Output the generated PDF to Browser with document name("RESERVATION-01-12-1979")
    //         $dompdf->stream("LISTE-COMMANDE-" . $date_star . "-" . $date_end);
    //     }
    // }
}
