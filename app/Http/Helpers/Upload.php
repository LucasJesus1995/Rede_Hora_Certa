<?php

namespace App\Http\Helpers;


use App\AtendimentoLaudo;
use App\AtendimentoLaudoImagens;
use App\Atendimentos;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ZipArchive;

class Upload
{

    public static function agendaPDFSIGA(UploadedFile $file)
    {
        $path = PATH_UPLOAD . "tmp/pdf/importacao/agenda/" . date('Ymd') . "/";
        self::recursive_mkdir($path);

        $file = is_object($file) ? $file : null;
        if ($file) {
            $name = camel_case(strtolower($file->getClientOriginalName()));

            $file->move($path, $name);

            return $path . $name;
        }

        return null;
    }

    /**
     * @param $atendimento_laudo
     * @param $file
     * @return AtendimentoLaudoImagens|bool
     */
    public static function laudoAtendimentoImagens($atendimento_laudo, $file)
    {
        $path = PATH_UPLOAD . "laudos/imagem/" . $atendimento_laudo . "/";
        self::recursive_mkdir($path);

        $image = is_object($file) ? $file : null;
        if ($image) {
            $atendimento_laudo = AtendimentoLaudo::get($atendimento_laudo);
            $atendimento = Atendimentos::get($atendimento_laudo->atendimento);
            $created = explode(" ", $atendimento['created_at']);
            $created = explode("-", $created[0]);

            $name = $atendimento_laudo->id . "-" . uniqid() . "." . strtolower($image->getClientOriginalExtension());

            foreach (['s' => 200] as $k => $size) {
                $file_s3 = "cies-sistema/imagens/laudo/{$created[0]}/{$created[1]}/{$created[2]}/{$atendimento['id']}/{$k}-{$name}";
                Image::make($image->getRealPath())->widen($size)->save($path . "{$k}-" . $name);
                if (Storage::disk('s3')->put($file_s3, file_get_contents($path . "{$k}-" . $name), 'private')) {
                    unlink($path . "{$k}-" . $name);
                }
            }

            $file_s3 = "cies-sistema/imagens/laudo/{$created[0]}/{$created[1]}/{$created[2]}/{$atendimento['id']}/{$name}";
            if (Storage::disk('s3')->put($file_s3, file_get_contents($image), 'private')) {
                return AtendimentoLaudoImagens::saveImagem($atendimento_laudo->id, $name);
            }
        }

        return false;
    }

    public static function getImagemLaudo($atendimento, $atendimento_laudo_imagem, $small = false, $local = false)
    {
        $created = explode(" ", $atendimento['created_at']);
        $created = explode("-", $created[0]);

        $filename = "cies-sistema/imagens/laudo/{$created[0]}/{$created[1]}/{$created[2]}/{$atendimento['id']}/{$atendimento_laudo_imagem['arquivo']}";

        $disk = \Storage::disk('s3');
        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
            'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
            'Key' => $filename,
        ]);

        $request = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

        if ($local) {
            $path_filename = PATH_UPLOAD . "tmp/" . date('d') . "/";
            self::recursive_mkdir($path_filename);
            file_put_contents($path_filename . $atendimento_laudo_imagem['arquivo'], file_get_contents((string)$request->getUri()));
            return $path_filename . $atendimento_laudo_imagem['arquivo'];
        }

        return (string)$request->getUri();
    }

    public function importacao_upload($file)
    {
        $file = is_object($file) ? $file : null;

        if ($file) {
            $path = PATH_UPLOAD . "importacao/usuario/";
            $this->recursive_mkdir($path);

            $filename = time() . '.' . $file->getClientOriginalExtension();

            $upload = $file->move($path, $filename);
            return $filename;
        }

        return false;
    }

    public function importacao_upload_agenda($file)
    {
        $file = is_object($file) ? $file : null;

        if ($file) {
            $path = PATH_UPLOAD . "importacao/agenda/";
            $this->recursive_mkdir($path);

            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            $upload = $file->move($path, $filename);
            return $filename;
        }

        return false;
    }

    public function importacao_upload_paciente($file)
    {
        $file = is_object($file) ? $file : null;

        if ($file) {
            $path = PATH_UPLOAD . "importacao/paciente/";
            $this->recursive_mkdir($path);

            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);

            return $filename;
        }

        return false;
    }

    public static function removerPath($path)
    {
        if (file_exists($path)) {
            if ($dd = opendir($path)) {
                while (false !== ($Arq = readdir($dd))) {
                    if ($Arq != "." && $Arq != "..") {
                        $Path = "{$path}/$Arq";
                        if (is_dir($Path)) {
                            self::removerPath($Path);
                        } elseif (is_file($Path)) {
                            unlink($Path);
                        }
                    }
                }
                closedir($dd);
            }
            rmdir($path);
        }
    }

    public static function recursive_mkdir($path, $mode = 0777)
    {
        $dirs = explode(DIRECTORY_SEPARATOR, $path);

        $path = '.';
        for ($i = 0; $i < count($dirs); ++$i) {
            $path .= DIRECTORY_SEPARATOR . $dirs[$i];
            if (!is_dir($path) && !mkdir($path, $mode, true)) {
                return false;
            }
            @chmod($path, 0777);
        }
        return true;
    }

    public static function uploadLaudoAWS($laudo, $content)
    {
        $created = explode(" ", $laudo->created_at);
        $created = explode("-", $created[0]);
        $file_name = "cies-sistema/laudo/{$created[0]}/$created[1]/$created[2]/" . $laudo->id . '-' . uniqid() . ".pdf";

        if (Storage::disk('s3')->put($file_name, $content, 'public')) {
            $laudo->url = AWS_S3_URL . $file_name;
            $laudo->save();
        }
    }

    public static function uploadAtendimentoArquivos($arquivo, $atendimento, $tipo, $date)
    {
        $created = explode("-", explode(" ", $date)[0]);

        $file_s3 = "cies-sistema/atendimento/arquivos/{$tipo}/{$created[0]}/$created[1]/$created[2]/" . $atendimento . '-' . uniqid() . ".pdf";
        if (Storage::disk('s3')->put($file_s3, file_get_contents($arquivo), 'private')) {
            return  $file_s3;
        }

        return null;
    }

    public static function getAtendimentoArquivos($filename)
    {
        $disk = \Storage::disk('s3');
        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
            'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
            'Key' => $filename,
        ]);

        $request = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+2 minutes');

        return (string)$request->getUri();
    }

}