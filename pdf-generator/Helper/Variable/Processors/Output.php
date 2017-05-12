<?php
/**
 * Created by PhpStorm.
 * User: eadesignpc
 * Date: 10/23/2016
 * Time: 8:45 AM
 */

namespace Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors;

use Eadesigndev\Pdfgenerator\Model\Pdfgenerator;
use mPDF;

/**
 * Class Output
 *
 * @package Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors
 */
class Output extends Pdf
{

    /**
     * @var array
     */
    private $PDFFiles = [];

    /**
     * @param $parts
     * @return string
     */
    public function _eaPDFSettings($parts)
    {

        $templateModel = $this->template;

        //@codingStandardsIgnoreLine
        $pdf = new mPDF();

        if (!$templateModel->getTemplateCustomForm()) {
            $pdf = $this->standardSizePdf($templateModel);
        }

        if ($templateModel->getTemplateCustomForm()) {
            $pdf = $this->customSizePdf($templateModel);
        }

        //@codingStandardsIgnoreStart
        $pdf->SetHTMLHeader(html_entity_decode($parts['header']));
        $pdf->SetHTMLFooter(html_entity_decode($parts['footer']));

        $pdf->WriteHTML($templateModel->getTemplateCss(), 1);

        $pdf->WriteHTML('<body>' . html_entity_decode($parts['body']) . '</body>');
        //@codingStandardsIgnoreEnd
        $tmpFile = $this->directoryList->getPath('tmp') .
            DIRECTORY_SEPARATOR .
            $this->source->getIncrementId() .
            '.pdf';

        $this->PDFFiles[] = $tmpFile;

        $pdf->Output($tmpFile, 'F');

        return null;
    }

    /**
     * @param bool $templateModel
     * @return string
     */
    public function PDFmerger($templateModel = false)
    {

        $files = $this->PDFFiles;
        $model = $this->template;

        if (!$templateModel) {
            /** @var Pdfgenerator $templateModel */
            $templateModel = $model;
        }
        //@codingStandardsIgnoreLine
        $pdf = new mPDF();

        if (!$templateModel->getTemplateCustomForm()) {
            $ori = $templateModel->getTemplatePaperOri();

            $arrayOri = explode('-', $ori);
            if (count($arrayOri) > 1) {
                $finalOri = $arrayOri[1];
            }

            $pdf = $this->standardSizePdf($templateModel, $finalOri);
        }

        if ($templateModel->getTemplateCustomForm()) {
            $pdf = $this->customSizePdf($templateModel);
        }

        $filesTotal = count($files);
        $fileNumber = 1;

        $pdf->SetImportUse();

        return $this->generateMerge($files, $pdf, $fileNumber, $filesTotal);
    }

    /**
     * @param $templateModel
     * @param string $finalOri
     * @return mPDF
     */
    private function standardSizePdf($templateModel, $finalOri = 'P')
    {

        //@codingStandardsIgnoreLine
        $pdf = new mPDF(
            '',
            $this->paperFormat(
                $templateModel->getTemplatePaperForm(),
                $templateModel->getTemplatePaperOri()
            ),
            0,
            '',
            $templateModel->getTemplateCustomL(),
            $templateModel->getTemplateCustomR(),
            $templateModel->getTemplateCustomT(),
            $templateModel->getTemplateCustomB(),
            9,
            9,
            $finalOri
        );
        return $pdf;
    }

    /**
     * @param $templateModel
     * @return mPDF
     */
    private function customSizePdf($templateModel)
    {
        //@codingStandardsIgnoreLine
        $pdf = new mPDF(
            '',
            [
                $templateModel->getTemplateCustomW(),
                $templateModel->getTemplateCustomH()
            ],
            0,
            '',
            $templateModel->getTemplateCustomL(),
            $templateModel->getTemplateCustomR(),
            $templateModel->getTemplateCustomT(),
            $templateModel->getTemplateCustomB(),
            9,
            9
        );

        return $pdf;
    }

    /**
     * @param $files
     * @param $pdf
     * @param $fileNumber
     * @param $filesTotal
     * @return mixed
     */
    private function generateMerge($files, $pdf, $fileNumber, $filesTotal)
    {
        foreach ($files as $fileName) {
            if ($this->file->isExists($fileName)) {
                $pagesInFile = $pdf->SetSourceFile($fileName);
                for ($i = 1; $i <= $pagesInFile; $i++) {
                    $tplId = $pdf->ImportPage($i);
                    $pdf->UseTemplate($tplId);
                    if (($fileNumber < $filesTotal) || ($i != $pagesInFile)) {
                        $pdf->WriteHTML('<pagebreak />');
                    }
                }
            }
            $fileNumber++;
        }

        $pdfToOutput = $pdf->Output('', 'S');

        foreach ($files as $fileName) {
            $this->file->deleteFile($fileName);
        }

        return $pdfToOutput;
    }
}
