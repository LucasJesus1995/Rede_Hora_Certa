<?php

namespace App\Console\Commands;

use App\Http\Helpers\Upload;

use Aws\S3\S3Client;
use Illuminate\Console\Command;
use \Exception;
use Illuminate\Support\Facades\Storage;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:backup-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup do banco de dados';

    private $_updateHomologacao = 0;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = config('db-backup.path');

        Upload::removerPath($path);
        Upload::recursive_mkdir($path);

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        echo "Iniciando BKP \n";
//        $this->call("db:backup");
        $comando = "mysqldump -u" . env('DB_USERNAME') . " -h" . env('DB_HOST_READ') . " -p" . env('DB_PASSWORD') . " " . env('DB_DATABASE') . " > {$path}" . date('YmdHi') . ".sql";
        echo $comando;
        echo "Fim BKP \n";

        $files = scandir($path);
        foreach ($files as $file) {
            $arquivo = explode(".", $file);

            if ($arquivo[1] == 'sql') {
//                if(date('N') == 1) {
//                    echo "Atualizando ambiente DEV \n";
//                    $comando = env('DB_LOCAL_MYSQLDUMP') . "mysql -h " . env('DB_HOST_DEV') . " -u" . env('DB_USERNAME_DEV') . " -p" . env('DB_PASSWORD_DEV') . " ".env('DB_DATABASE_DEV')." < " . $path . $file;
//                    exec($comando);
//                }

                echo "Compactando arquivo\n";
                exec("gzip -9 {$path}{$file}");

                $this->_sendS3($path);
            }
        }
    }

    private function _sendS3($path)
    {
        echo "Enviando S3 \n";
        $path_remote = "cies-sistema/db/dumps/" . date('Y') . "/" . date('m') . "/" . date('d') . "/";

        $files = scandir($path);

        foreach ($files as $file) {
            $arquivo = explode(".", $file);

            if (count($arquivo) == 3 && $arquivo[2] == 'gz') {
                try {
                    if (Storage::disk('s3')->put($path_remote . $file, file_get_contents($path . $file), 'private')) {
                        unlink($path . $file);
                    }

                } catch (Exception $e) {
                    echo "\n------------ Exception ------------";
                    echo "\n" . $e->getMessage();
                    echo "\n------------ ------------ ------------\n";
                }
            }

        }
    }

}
