<?php

namespace App\Controllers;

class Documentos extends BaseController
{
    public function bajar($id)
    {
        $attachments = $this->zoho->getAttachments("Products", $id);
        foreach ($attachments as $attchmentIns) {
            $file = $this->zoho->downloadAttachment("Products", $id, $attchmentIns->getId(), WRITEPATH . "uploads");
            return $this->response->download($file, null)->setFileName('Documentos.pdf');
        }
    }

    public function subir($id)
    {
        if ($files = $this->request->getFiles()) {
            foreach ($files['documentos'] as $file) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads', $newName);
                $this->zoho->uploadAttachment("Deals", $id, WRITEPATH . 'uploads/' . $newName);
            }
            session()->setFlashdata('alerta', 'Documentos adjuntados correctamente');
            //ir a los detalles del registro
            return redirect()->to(site_url("emisiones"));
        }
        $documentos = $this->zoho->getAttachments("Deals", $id);
        return view('documentos/subir', ["documentos" => $documentos, "id" => $id]);
    }
}
