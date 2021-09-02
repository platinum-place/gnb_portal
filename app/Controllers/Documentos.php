<?php

namespace App\Controllers;

use App\Libraries\Zoho;

class Documentos extends BaseController
{
    public function bajar($id)
    {
        $zoho = new Zoho;
        $attachments = $zoho->getAttachments("Products", $id);
        foreach ($attachments as $attchmentIns) {
            $file = $zoho->downloadAttachment("Products", $id, $attchmentIns->getId(), WRITEPATH . "uploads");
            return $this->response->download($file, null)->setFileName('Documentos.pdf');
        }
    }

    public function subir($id)
    {
        $zoho = new Zoho;
        if ($files = $this->request->getFiles()) {
            foreach ($files['documentos'] as $file) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads', $newName);
                $zoho->uploadAttachment("Deals", $id, WRITEPATH . 'uploads/' . $newName);
            }
            session()->setFlashdata('alerta', 'Documentos adjuntados correctamente');
            //ir a los detalles del registro
            return redirect()->to(site_url("emisiones"));
        }
        $documentos = $zoho->getAttachments("Deals", $id);
        return view('documentos/subir', ["documentos" => $documentos, "id" => $id]);
    }
}
