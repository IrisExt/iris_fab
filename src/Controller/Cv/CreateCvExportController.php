<?php


namespace App\Controller\Cv;


use App\Controller\BaseController;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class CreateCvExportController extends BaseController
{
    /**
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     * @Route("/cv/createWord")
     */
    public function createCvWord(Request $request)
    {

        $cvPage = $this->render('cvPersonne/cv_word.html.twig');
//$t = 'tte';
//        $phpWord = new PhpWord();
        $templete = $_SERVER['DOCUMENT_ROOT'].'\cv\templeteCv.docx';
        $filename = $_SERVER['DOCUMENT_ROOT'].'\cv\result.docx';
//        $filename ='result.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
//        $phpWord->loadTemplate($templete);
//        $phpWord->setValue('Name', 'John Doe');
//        $phpWord->setValue(array('City', 'Street'), array('Detroit', '12th Street'));

        $templateProcessor = new TemplateProcessor($templete);
        $templateProcessor->setValue('firstname', 'Adldeeddden');
        $templateProcessor->setValue('lastname', 'Dzzoe');
        $templateProcessor->saveAs($filename);

        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
//        $phpWord = \PhpOffice\PhpWord\IOFactory::load($filename); // Read the temp file
//        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

//        $xmlWriter->save('result.docx');
//        header("Location: result.docx") ;
//        $section = $phpWord->addSection();
//        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $cvPage, false);

//          $fileName = 'result.docx';
//        $temp_file = tempnam(sys_get_temp_dir(), $filename);
//        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
//        $templateProcessor->saveAs($fileName);
//        $phpWord = \PhpOffice\PhpWord\IOFactory::load($fileName); // Read the temp file
//        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($templateProcessor, 'Word2007');
//        $xmlWriter->save('result.docx');
//        dd( $templateProcessor->getVariables());
        // Saving the document as ODF file...
//        $xmlWriter->save('result.docx');

//        return $this->file($filename, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

        return new Response('word generated OK');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Mpdf\MpdfException
     * @Route("/cv/createPdf")
     */
    public function createCvPDF(Request $request)
    {

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output('filename.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        return new Response('Excel generated OK');
    }

    /**
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     * @Route("/cv/createWord")
     */
    public function createCvExcel(Request $request)
    {

        $spreadsheet = new Spreadsheet();
//        dd($request->server->get('DOCUMENT_ROOT'));
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Test ')
            ->setTitle('Cv');

        $writer = new Xlsx($spreadsheet);

        $fileName = 'cv_1.docx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $dirctory = $request->server->get('DOCUMENT_ROOT').'/cv';


        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

        return new Response('word generated OK');
    }
}