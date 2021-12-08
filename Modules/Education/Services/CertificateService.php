<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Certificate;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class CertificateService extends ServiceContract {
    public function __construct(Certificate $certificate)
    {
        $this->model = $certificate;
    }
    public function loadDataFromFile()
    {
        $inputFileType = 'Csv';
        $inputFileName = storage_path('/export-templates/students.csv');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $limit = $sheet->getHighestRow();
        for($i=2; $i <= $limit; $i++){
            $data = [
                'certificate_id' => $sheet->getCell('C' . $i)->getValue(),
                'registered_number' => $sheet->getCell('D' . $i)->getValue(),
                'region' => $sheet->getCell('E' . $i)->getValue(),
                'full_name' => $sheet->getCell('F' . $i)->getValue(),
                'full_name_english' => $sheet->getCell('G' . $i)->getValue(),
                'subject' => $sheet->getCell('H' . $i)->getValue(),
                'subject_english' => $sheet->getCell('I' . $i)->getValue(),
                'phone' => $sheet->getCell('J' . $i)->getValue(),
                'given_date' => null
            ];
            $this->model->firstOrCreate(['certificate_id' => $data['certificate_id'], 'subject' => $data['subject']], $data);
        }
        return 'Muvofaqqiyatli yuklandi';
    }
    public function generateFile2($id)
    {
        $model = $this->model->find($id)->toArray();
        $templateProcessor = new TemplateProcessor(storage_path('/export-templates/certificate_template.docx'));
        foreach ($model as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }
        $path_docx = '/temp/certificate_' . ($model['certificate_id']) .'.docx';

        $pathToSaveDocx = storage_path('/export-templates' . $path_docx);
        $templateProcessor->saveAs($pathToSaveDocx);

        return Storage::disk('export_templates')->download($path_docx);
    }
    public function generateFile()
    {
        $models = $this->model->all()->toArray();
        $chunk = [];
        foreach($models as $model){
            if(count($chunk) == 2){
                $this->generateFile3($chunk);
                $chunk = [];
            }
            $chunk[] = $model;
        }
        if(count($chunk)){
            $this->generateFile3($chunk);
        }

        return 'ok';
    }
    public function generateFile3($chunk){
        $model1 = $chunk[0];
        $model2 = $chunk[1]??null;
        $name = $model1['id'] . $model1['certificate_id'];
        $templateProcessor = new TemplateProcessor(storage_path('/export-templates/certificate_template_2.docx'));
        foreach ($model1 as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }
        if($model2){

            $name .= '-'. $model2['id']. $model2['certificate_id'];
            foreach ($model2 as $key => $value) {
                $templateProcessor->setValue($key.'2', $value);
            }
        }
        $path_docx = '/temp/certificate-' . $name .'.docx';

        $pathToSaveDocx = storage_path('/export-templates' . $path_docx);
        $templateProcessor->saveAs($pathToSaveDocx);
    }
}