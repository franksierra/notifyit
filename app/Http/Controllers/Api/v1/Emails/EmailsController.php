<?php

namespace App\Http\Controllers\api\v1\Emails;

use App\Http\Requests\ApiRequest;
use App\Jobs\SendEmailJob;
use App\Http\Controllers\Controller;

class EmailsController extends Controller
{
    public function queue(ApiRequest $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'from' => 'required|email|max:255',
            'to' => 'required|email',
            'subject' => 'required|max:255',
            'body' => 'required',
        ]);
        $details = [
            'app_id' => 0,
            'uid' => uniqid("mail_"),
            'name' => $request->get('name'),
            'from' => $request->get('from'),
            'reply_to' => $request->get('reply_to', $request->get('from')),

            'to' => $request->get('to'),
            'cc' => $request->get('cc', '[]'),
            'bcc' => $request->get('bcc', '[]'),
            'subject' => $request->get('subject'),
            'body' => $request->get('body'),
            'alt_body' => $request->get('alt_body', ''),

            'embedded' => $request->get('embedded', '[]'),
            'attachments' => $request->get('attachments', '[]'),

        ];
        dispatch(new SendEmailJob($details));

        return response()->json([
            'status' => 200,
            'mail_uid' => $details['uid']
        ]);
//        $yo = $this->me();
//        $configuracion_email = $this->Configuracion_email->get_by(
//            array(
//                "id_app" => $yo->app->app_id
//            )
//        );
//        if (!$configuracion_email['count'] > 0) {
//            $this->rest_response->set_status(MY_Rest::HTTP_CONFLICT);
//            $this->rest_response->set_notices("No se encontraron credenciales configuradas para esta aplicación");
//            $this->response();
//        }
//        $configuracion = $configuracion_email['data'][0];
//        $config_smtp = array();
//        $config_smtp['smtp_host'] = $configuracion->smtp_host;
//        $config_smtp['smtp_user'] = $configuracion->smtp_user;
//        $config_smtp['smtp_pass'] = $configuracion->smtp_pass;
//        $config_smtp['smtp_port'] = $configuracion->smtp_port;
//        $config_smtp['mailtype'] = $configuracion->mailtype;
//        /*
//         * Seteamos la configuración del servidor de correo asignado a la aplicación
//         */
//        $this->phpmail->inicializar($config_smtp);
//        $this->phpmail->de($this->post("de"), $this->post("nombre"));
//        $this->phpmail->responder_a($this->post("responder_a") != NULL ? $this->post("responder_a") : $this->post("de"));
//        $this->phpmail->asunto($configuracion->subject_prefix . $this->post("asunto"));
//
//
//        $para = explode(",", $this->post("para"));
//        foreach ($para as $mail) {
//            if (!empty($mail)) {
//                $this->phpmail->para($mail);
//            }
//        }
//        $cc = explode(",", $this->post("cc"));
//        foreach ($cc as $mail) {
//            $this->phpmail->copia($mail);
//        }
//        $cco = explode(",", $this->post("bcc"));
//        foreach ($cco as $mail) {
//            $this->phpmail->copia_oculta($mail);
//        }
//        $this->phpmail->cuerpo($this->post("cuerpo"));
//        $this->phpmail->cuerpo_alternativo($this->post("cuerpo_alternativo"));
//
//        /*
//         * Seteamos los archivos incrustados
//         */
//        $_embebidos = json_decode($this->post("embebidos"));
//        if ($_embebidos != NULL) {
//            foreach ($_embebidos as $cid => $archivo) {
//                $formato = $archivo->formato;
//                $contenido = $archivo->b64;
//                $archivo->tmpname = sys_get_temp_dir() . "/{$cid}-" . uniqid() . ".{$formato}";
//
//                file_put_contents($archivo->tmpname, base64_decode($contenido));
//                $this->phpmail->embeber_imagen($cid, $archivo->tmpname);
//            }
//        }
//
//        /*
//         * Seteamos los archivos adjuntos
//         */
//        $_adjuntos = json_decode($this->post("adjuntos"));
//        if ($_adjuntos != NULL) {
//            foreach ($_adjuntos as $nombre => $archivo) {
//                $formato = $archivo->formato;
//                $contenido = $archivo->b64;
//                $archivo->tmpname = sys_get_temp_dir() . "/{$nombre}-" . uniqid() . ".{$formato}";
//                file_put_contents($archivo->tmpname, base64_decode($contenido));
//                $this->phpmail->adjunto($nombre, $archivo->tmpname);
//            }
//        }
//
//
//        $resultado_envio = $this->phpmail->enviar();
//
//        /*
//         * Esto esta aqui para limpiar los archivos temporales,
//         * no quiero estar llenando el servidor con archivos temporales
//         */
//        if ($_embebidos != NULL) {
//            foreach ($_embebidos as $cid => $archivo) {
//                unlink($archivo->tmpname);
//            }
//        }
//
//        if ($_adjuntos != NULL) {
//            foreach ($_adjuntos as $nombre => $archivo) {
//                unlink($archivo->tmpname);
//            }
//        }
    }
}
