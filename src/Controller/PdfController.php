<?php

namespace App\Form;

use App\Entity\Opportunite;
use App\Entity\Postuler;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    /**
     * @Route("/pdf/{filename}", name="pdf_view")
     */
    public function viewPdf($filename)
    {
        // Assuming the PDF files are stored in the public/assets/img directory
        $filePath = $this->getParameter('project_directory') . '/' . $filename;

        // Check if the file exists
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist.');
        }

        // Return a response with the PDF file
        return new BinaryFileResponse($filePath, 200, [], false, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}