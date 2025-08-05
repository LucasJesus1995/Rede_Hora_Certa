<?php

namespace App\Http\Controllers;

use App\AgendaCirurgico;
use App\Agendas;
use App\Cirurgico;
use App\ContatoPaciente;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\Pacientes;
use App\Profissionais;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CirurgicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($clear = "false", $excel = false) {

        session_start();

        if($clear == "clear"){
            $_SESSION['data_inicio_cirurgico'] = date('d/m/Y');
            $_SESSION['data_final_cirurgico'] = date('d/m/Y');
            $_SESSION['paciente_cirurgico'] = '';
            $_SESSION['medico_cirurgico'] = '';
        }
        
        $view = View('admin.cirurgico.index');

        if(!isset($_SESSION['data_inicio_cirurgico'])){
            $_SESSION['data_inicio_cirurgico'] = date('d/m/Y');
        }

        if(!isset($_SESSION['data_final_cirurgico'])){
            $_SESSION['data_final_cirurgico'] = date('d/m/Y');
        }
        
        if(!isset($_SESSION['paciente_cirurgico'])){
            $_SESSION['paciente_cirurgico'] = '';
        }

        if(!isset($_SESSION['medico_cirurgico'])){
            $_SESSION['medico_cirurgico'] = '';
        }

        $medicos = Profissionais::select('id', 'nome', 'cro')
                                ->where('type', 1)
                                // ->where('ativo', 1)
                                ->orderBy('nome', 'ASC')
                                ->get();

        $view->data_inicio_cirurgico = $_SESSION['data_inicio_cirurgico'];

        if($_SESSION['data_inicio_cirurgico'] == $_SESSION['data_final_cirurgico']){
            $view->data_final_cirurgico = '';
        } else {
            $view->data_final_cirurgico = $_SESSION['data_final_cirurgico'];
        }
        $view->paciente = $_SESSION['paciente_cirurgico'];
        $view->selMedico = $_SESSION['medico_cirurgico'];
        $view->medicos = $medicos;

        $data_inicio_cirurgico = \App\Http\Helpers\Util::Date2DB($_SESSION['data_inicio_cirurgico']);        
        $data_final_cirurgico = \App\Http\Helpers\Util::Date2DB($_SESSION['data_final_cirurgico']);        

        if(!empty($_SESSION['paciente_cirurgico'])){
            $wherePaciente = "pacientes.nome LIKE '%{$_SESSION['paciente_cirurgico']}%'";
        } else {
            $wherePaciente = "1";
        }

        if(!empty($_SESSION['medico_cirurgico'])){
            $whereMedico = "atendimento.medico = {$_SESSION['medico_cirurgico']}";
        } else {
            $whereMedico = "1";
        }

        // dd($whereMedico);

        $agendas = Agendas::select(
            [
                'agendas.id AS agenda_id',
                'arenas.nome AS arena',
                'agendas.data AS data_real',
                DB::raw('DATE_FORMAT(agendas.data, "%Y-%m-%d") AS data_atendimento'),
                DB::raw('(SELECT data FROM agendas AS ag WHERE ag.data > agendas.data AND ag.paciente = agendas.paciente ORDER BY ag.data DESC LIMIT 1) AS saida'), // agendamento posterior
                'pacientes.nome AS paciente',
                'pacientes.id AS paciente_id',
                'pacientes.cns AS sus',
                'linha_cuidado.nome AS especialidade',
                'profissionais.nome AS medico',
                'tipo_atendimento.nome AS tipo_atendimento',
                'conduta_principal.nome AS conduta_principal',
                'conduta_secundaria.nome AS conduta_secundaria',
                'regulacao.nome AS regulacao',
                'atendimento_auxiliar.conduta_opcao AS lateralidade',
                DB::raw('atendimento_auxiliar.conduta_descricao AS descricao'),
            ]
        )
        ->join('arenas', 'arenas.id', '=', 'agendas.arena')
        ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
        ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
        ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
        ->leftJoin('profissionais', 'atendimento.medico', '=', 'profissionais.id')
        ->join('atendimento_auxiliar', 'atendimento_auxiliar.atendimento', '=', 'atendimento.id')
        ->leftJoin('tipo_atendimento', 'tipo_atendimento.id', '=', 'atendimento.tipo_atendimento')
        ->leftJoin('condutas as conduta_principal', 'atendimento_auxiliar.conduta', '=', 'conduta_principal.id')
        ->leftJoin('condutas as conduta_secundaria', 'atendimento_auxiliar.conduta_secundaria', '=', 'conduta_secundaria.id')
        ->leftJoin('condutas as regulacao', 'atendimento_auxiliar.conduta_regulacao', '=', 'regulacao.id')
        ->whereBetween('agendas.data', array($data_inicio_cirurgico . ' 00:00:00', $data_final_cirurgico . ' 23:59:59'))
        ->whereRaw('cast(atendimento_auxiliar.conduta as  unsigned integer) > 0')
        ->whereIn('agendas.status', [6, 8, 98, 99]) // 6 - Finalizado, 8 - Finalizado (Cirurgico), 98 - Faturado (Parcial), 99 - Faturado (Total)
        ->where('conduta_principal.nome', '<>', 'ALTA') // Paciente não recebeu Alta
        ->where('conduta_principal.nome', '<>', 'REGULACAO') // Paciente não recebeu Alta
        ->whereRaw($wherePaciente)
        ->whereRaw($whereMedico)
        ->orderBy('agendas.data', 'ASC');

        if($excel) {

            $data = $agendas->get();

           if (!empty($data[0])) {
                $headers = ['ARENA', 'DATA ATENDIMENTO', 'DATA SAÍDA', 'NOME DO PACIENTE', 'LINHA CUIDADO', 'MÉDICO', 'TIPO DE ATENDIMENTO', 'CONDUTA PRINCIPAL'];
                $lines[] = implode(";", $headers);
                foreach ($data->toArray() as $row) {
                    unset($row['agenda_id']);
                    unset($row['data_real']);
                    unset($row['paciente_id']);
                    unset($row['sus']);
                    unset($row['conduta_secundaria']);
                    unset($row['regulacao']);
                    unset($row['lateralidade']);
                    unset($row['descricao']);
                    if(!empty($row['saida'])){
                        $row['saida'] = date('Y-m-d', strtotime($row['saida']));
                    }
                    $lines[] = implode(";", $row);
                }

                $path = PATH_FILE_RELATORIO . 'excel/cirurgico/' . Util::getUser() . '/';
                // dd($path);
                Upload::recursive_mkdir($path);
                $filename = "atendimentos-cirurgico.csv";
                file_put_contents(public_path($path . $filename), implode("\r\n", $lines));

                $link = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename;

                return redirect($link);

            } else {
                return redirect('/admin/cirurgico/list');
            }

        } else {
            $agendas = $agendas->paginate(20);
        }

      
        $view->agendas = $agendas;

        return $view;
        

    }

    public function filtro(Request $request)
    {

        session_start();
        // recebe os dados do formulário
        $dados = $request->all();
        // grava os valores em sessão
        $_SESSION['data_inicio_cirurgico'] = $dados['data_inicio_cirurgico'];
        if(!empty($dados['data_final_cirurgico'])){
            $_SESSION['data_final_cirurgico'] = $dados['data_final_cirurgico']; 
        } else {
            $_SESSION['data_final_cirurgico'] = $dados['data_inicio_cirurgico'];        
        }
        $_SESSION['paciente_cirurgico'] = $dados['paciente'];
        $_SESSION['medico_cirurgico'] = $dados['medico'];
        // redireciona para o index
        return redirect('/admin/cirurgico/list');

    }

    public function agendas($paciente_id, $data){

        $paciente = Pacientes::find($paciente_id);

        $agendas = Agendas::select('agendas.id', 'agendas.data', 'arenas.nome AS arena', 'agendas.status', 'linha_cuidado.nome AS linha_cuidado')
                            ->join('arenas', 'agendas.arena', '=', 'arenas.id')
                            ->join('linha_cuidado', 'agendas.linha_cuidado', '=', 'linha_cuidado.id')
                            ->where('agendas.paciente', $paciente_id)
                            ->where('agendas.data', '>=', $data . ' 00:00:00')
                            ->orderBy('agendas.data', 'ASC')
                            ->get();
        
        return View('admin.cirurgico.agendas', compact('agendas', 'paciente'));
 
    }

    public function getContatoPaciente($paciente_id, $agenda){

        $paciente = Pacientes::find($paciente_id);

        $contatos = ContatoPaciente::select('contato_pacientes.created_at',  'contato_pacientes.status', 'contato_pacientes.id', 
                                            'contato_pacientes.descricao', 'users.name AS usuario')
                                    ->join('users', 'contato_pacientes.user', '=', 'users.id')
                                    ->where('paciente', $paciente_id)
                                    ->where('agenda', $agenda)
                                    ->orderBy('contato_pacientes.created_at', 'DESC')
                                    ->get();
        
        return View('admin.cirurgico.contato_pacientes', compact('contatos', 'paciente', 'agenda', 'paciente_id'));       

    }

    public function contatoSalvar(Request $request){

        $dados = $request->all();
        // die(json_encode($dados));

        if(empty($dados['contato_id'])){
            unset($dados['contato_id']);
            $dados['user'] = Auth::user()->id; 
            ContatoPaciente::create($dados);
        } else {
            $contato = ContatoPaciente::find($dados['contato_id']);
            unset($dados['contato_id']);
            $contato->update([
                'status' => $dados['status'],
                'descricao' => $dados['descricao']
            ]);
        }

        die;

    }

    public function editContato($id){

        $contato = ContatoPaciente::find($id);

        return json_encode($contato);

    }


   
   
}
