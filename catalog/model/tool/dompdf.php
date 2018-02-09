<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class ModelToolDompdf extends Model {
	public function getFile($html, $file_name, $folder, $style = 'pdf') {
        $file = array();

        //HTML to PDF
        if ($style == 'pdf') {
            require_once(DIR_SYSTEM . 'extra/dompdf/autoload.inc.php');

            $options = new Options();
            $options->set('defaultFont', 'DejaVuSans');

            $pdf = new Dompdf($options);

            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();

            $output = $pdf->output();

            $file_name = $file_name . '.pdf';
            $file_location = DIR_DOWNLOAD . ($folder ? $folder . '/' : '') . $file_name;

            file_put_contents($file_location, $output);

            $file = array(
                'name'     => $file_name,
                'location' => $file_location,
                'folder'   => $folder
            );
        }

        return $file;
	}

    public function deleteFile($file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
