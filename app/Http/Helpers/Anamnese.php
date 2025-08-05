<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 14/07/15
 * Time: 19:43
 */

namespace App\Http\Helpers;


use App\Agendas;
use App\AnamnesePerguntas;

class Anamnese
{

    public static function DataHeader($agenda)
    {
        $data = Agendas::select('agendas.id', 'agendas.data', 'agendas.status', 'pacientes.nome', 'pacientes.nome_social', 'pacientes.mae', 'pacientes.sexo', 'pacientes.rg', 'pacientes.nascimento', 'agendas.paciente', 'pacientes.cns', 'atendimento.preferencial', 'procedimentos.nome as procedimento',
            'linha_cuidado.nome as linha_cuidado')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->leftjoin('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->leftjoin('procedimentos', 'procedimentos.id', '=', 'atendimento.procedimento')
            ->where('agendas.id', $agenda)
            ->get()
            ->first();

        return $data = !empty($data) ? $data->toArray() : null;
    }

    public static function QuestionarioEspecifico()
    {
        return [1, 4, 5, 10, 11, 14, 17, 12, 13, 252, 27, 21];
    }

    public static function AcolhimentoEnfermagem()
    {
        return [15, 16, 18, 26, 265, 267, 266, 269];
    }


    public static function DiagnosticoEnfermagem()
    {
        return [31, 32, 33, 34, 35];
    }

    public static function EndoscopiaEnfermagem()
    {
        return [31, 32, 33, 34, 35];
    }

    public static function Eletroneuromiografia()
    {
        return [189, 190, 191, 192, 193, 194, 195, 196, 197, 198];
    }

    public static function Intercorrencia()
    {
        return [236, 237, 238, 239, 240];
    }

    public static function CheckList()
    {
        return [2, 3, 6, 7, 36, 8, 9, 248, 249, 250, 251];
    }

    public static function SinaisVitais()
    {
        return [205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235];
    }

    public static function Rasteabilidade()
    {
        return [241, 242, 243, 244, 245, 246, 247];
    }

    public static function PerguntasAnamneseMedicaUltrasom()
    {
        return [253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264];
    }

    public static function PerguntasByLinhaCuidado($linha_cuidado)
    {
        $perguntas = [];

        switch ($linha_cuidado) {
            case 3 :
                $perguntas = self::PerguntasLinhaCuidado(50, 88);
                break;
            case 1 :
            case 2 :
                $perguntas = self::PerguntasLinhaCuidado(100, 119);
                break;
            case 4 :
                $perguntas = self::PerguntasLinhaCuidado(150, 188);
                break;
            case 6 :
                $perguntas = self::PerguntasLinhaCuidado(189, 198);
                break;
        }

        return $perguntas;
    }

    public static function PerguntasLinhaCuidadoArray($ids)
    {
        return AnamnesePerguntas::select('id', 'cid', 'nome')
            ->whereIn('anamnense_perguntas.id', $ids)
            ->orderBy('nome', 'asc')
            ->get()
            ->toArray();
    }

    public static function PerguntasLinhaCuidado($ini, $fim)
    {
        return AnamnesePerguntas::select('id', 'cid', 'nome')
            ->where('anamnense_perguntas.id', '>=', $ini)
            ->where('anamnense_perguntas.id', '<=', $fim)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }

    public static function MountASKPrint($tipo)
    {
        return self::TipoResposta($tipo);

    }

    public static function MountASK($id, $tipo = false, $multi = false, $resposta = array(), $__key = null, $permission = true)
    {
        $res = [];

        if ($tipo) {
            $tipos = self::TipoResposta($tipo);
            $disabled = (!$permission) ? "disabled='disabled'" : null;

            if ($tipos) {
                $res[] = "<div class='row'>";
                foreach ($tipos as $key => $row) {
                    if (is_numeric($key)) {

                        $checked = null;
                        if (array_key_exists($id, $resposta)) {
                            $resp_arr = explode(",", $resposta[$id]['value']);

                            foreach ($resp_arr as $resp) {
                                if ($resp == $key) {
                                    $checked = "checked='checked'";
                                    break;
                                }
                            }
                        }

                        $type = ($multi) ? 'checkbox' : 'radio';

                        $res[] = "<div class='col-md-3'>
                                            <label class='radio-inline text-medium'>
                                                <input type='{$type}' name='value_{$id}' value='{$key}' id='value_{$id}' rel='{$id}' multi='{$multi}' class='' {$checked} key='{$__key}' {$disabled} />
                                                {$row}

                                            </label>
                                     </div>";
                    } else {
                        $value = (array_key_exists($id, $resposta)) ? $resposta[$id]['value_descricao'] : null;
                        $res[] = "<div class='form-group ' style='margin-top: 28px' >
                                        <div class='col-md-12'>
                                            <input name='value_descricao_{$id}'  value='{$value}' id='value_descricao_{$id}' multi='{$multi}' class='form-control col-md-12' type='text' placeholder='{$row}' rel='{$id}' key='{$__key}' {$disabled} />
                                        </div>
                                      </div>";
                    }
                }
                $res[] = "</div>";
            }
        }

        echo implode(" ", $res);
        return;
    }

    public static function TipoResposta($key)
    {
        $tipo = array(
            '1' => array(
                '1' => 'Sim',
                '2' => 'Não'
            ),
            '2' => array(
                '1' => 'Sim',
                '2' => 'Não',
                '?' => 'Qual(is)?'
            ),
            '3' => array(
                '1' => 'Liquida',
                '2' => 'Semi Liquida',
                '3' => 'Com resíduo',
                '4' => 'Pastoso'
            ),
            '4' => array(
                '1' => 'Dor Abdominal',
                '2' => 'Náuseas-Vômitos',
                '3' => 'Vômitos com sangue',
                '4' => 'Fezes com sangue',
                '5' => 'Refluxo-azia',
                '6' => 'Perda de peso',
                '7' => 'Constipação intestinal',
                '8' => 'Diarréia',
                '9' => 'Anemia'
            ),
            '5' => array(
                '1' => 'Carro',
                '2' => 'Ônibus',
                '3' => 'Moto',
                '4' => 'Metro',
                '5' => 'Trem',
                '?' => 'Outros'
            ),
            '6' => array(
                '1' => 'Sim',
                '2' => 'Não',
                '?' => 'Medicamento',
                '?' => 'Dose',
                '?' => 'Ultima Consulta'
            ),
            '7' => array(
                '1' => 'Asma',
                '2' => 'Bronquite',
                '?' => 'Data ultima crise',
                '3' => 'Tiroide',
                '4' => 'HAS',
                '5' => 'DM',
                '6' => 'Anemia'
            ),
            '8' => array(
                '1' => 'Câncer',
                '2' => 'Diabetes',
                '3' => 'Coração?',
                '4' => 'Pulmão'
            ),
            '9' => array(
                '1' => 'Procedimentos invasivos',
                '2' => 'Cateter venoso',
                '?' => 'Outros'
            ),
            '10' => array(
                '1' => 'Bradicardia',
                '2' => 'Taquicardia',
                '3' => 'Palpitações',
                '4' => 'Palidez cutânea',
                '5' => 'Sudorese',
                '6' => 'Rebaixamento do nivel de consciencia',
                '7' => 'Agitação'
            ),
            '11' => array(
                '1' => 'Modo de transporte',
                '2' => 'Déficit físico',
                '3' => 'Ambiente',
                '4' => 'Elevadores',
                '5' => 'Escadas',
                '6' => 'Piso'
            ),
            '12' => array(
                '1' => 'Sialorreia',
                '2' => 'Oxigenação diminuída',
                '3' => 'Obstrução das vias aéreas'
            ),
            '13' => array(
                '1' => 'Jejum prolongado',
                '2' => 'Hipoglicemiante',
                '3' => 'Uso de insulina',
                '4' => 'Jejum para exames'
            ),
            '14' => array(
                '?' => 'Resposta Generica'
            ),
            '15' => array(
                '1' => 'Sem',
                '2' => 'Leve',
                '3' => 'Moderada',
                '4' => 'Forte',
                '5' => 'Insuportável'
            ),
            '16' => array(
                '1' => 'Esteatose Hepática',
                '2' => 'Nefrolitíase',
                '3' => 'Colelitíase',
                '4' => 'Cistos Renal',
                '5' => 'Cistos Hepáticos',
                '6' => 'Aumento Prostático',
                '7' => 'Pancreatites',
                '8' => 'Normal',
                '9' => 'Cistos Pancreáticos',
                '?' => 'Outro'
            ),
            '17' => array(
                '1' => 'Normal',
                '2' => 'Mioma',
                '3' => 'Cistos de Ovários',
                '4' => 'Massas',
                '5' => 'Aumento de Próstata',
                '?' => 'Outros'
            ),
            '18' => array(
                '1' => 'Normal',
                '2' => 'Nódulos',
                '3' => 'Cistos',
                '?' => 'Outros'
            ),
            '19' => array(
                '1' => 'Normal',
                '2' => 'Aumento da Próstata',
                '?' => 'Outros'
            ),
            '20' => array(
                '1' => 'Normal',
                '2' => 'Mioma',
                '3' => 'Cistos',
                '?' => 'Outros'
            ),
            '21' => array(
                '1' => 'Ombro',
                '2' => 'Punho',
                '3' => 'Cotovelo',
                '4' => 'Dedos',
                '5' => 'Coxo-Femoral',
                '6' => 'Joelho',
                '7' => 'Tornozelo',
                '?' => 'Outros'
            ),
            '22' => array(
                '1' => 'Direito',
                '2' => 'Esquerdo',
                '3' => 'Bilateral'
            ),
            '23' => array(
                '1' => 'Normal',
                '2' => 'Tendeites',
                '3' => 'Derrame'
            ),
            '24' => array(
                '1' => 'Infarto',
                '2' => 'AVC',
                '3' => 'Cancer',
                '4' => 'Depressao'
            ),
            '25' => array(
                '1' => 'Infarto',
                '2' => 'AVC',
                '3' => 'Cancer'
            ),
            '26' => array(
                '1' => 'HAS',
                '2' => 'DM',
                '3' => 'Colesterol',
                '4' => 'Cancer',
                '5' => 'Alergia',
                '6' => 'Asma',
                '7' => 'Bronquite',
                '8' => 'Tabagismo',
                '9' => 'Tioreoide',
                '10' => 'Insuficiência Renal'

            ),
            '27' => array(
                '1' => 'Pressão',
                '2' => 'Diabete',
                '3' => 'AAs / Antiagregante',
                '4' => 'Anticoagulante',
                '5' => 'Depressão',
                '6' => 'Cardiovascular',
                '?' => 'Outros'
            ),
            '28' => array(
                '1' => 'Psicologico',
                '2' => 'Psiquiatrico'
            ),
            '29' => array(
                '1' => 'Pressão',
                '2' => 'Diabete',
                '3' => 'Cardiovascular',
                '?' => 'Outros'
            ),
        );

        return ($key && array_key_exists($key, $tipo)) ? $tipo[$key] : false;
    }

    public static function termoConsentimentoInformado($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];

        switch ($linha_cuidado) {
            case $linha_cuidado == 45 and $sub_especialidade == 1 :
                $data["1"] = "A cirurgia de remoção da catarata (facectomia) é realizada com vistas à recuperação total ou parcial da visão do olho afetado. A extensão da recuperação visual vai depender da existência ou não de doenças ou alterações de outras estruturas oculares associadas à catarata (doenças da córnea, doenças da retina e do nervo óptico, principalmente) e, igualmente, da magnitude dos riscos e complicações que podem ocorrer durante e após a cirurgia.";
                $data["2"] = "Riscos e complicações: A cirurgia da catarata (facectomia) necessita da abertura do globo ocular e isso expõe o olho a riscos de hemorragias e infecções. O trauma cirúrgico, mesmo sem intercorrências, pode precipitar, em olhos predispostos, complicações retinianas (edema/inchaço, hemorragias/sangramentos e descolamento de retina), corneanas (lesões endoteliais, edemas) e processos inflamatórios (uveítes). A implantação da lente intra-ocular, procedimento padrão, pode não ser possível ou aconselhável, sempre que isso possa concorrer para aumentar as chances de complicações que venham a comprometer o olho e diminuir a possibilidade de recuperação da visão. Há ainda o risco de acidente com perfuro cortante, ao qual equipe assistencial e paciente estão expostos, neste caso deve-se seguir as condutas padronizadas institucionalmente. Cirurgias que envolvem os dois lados do corpo comumente acompanham assimetrias discretas ou irregularidades de superfície, não significando, no entanto, um mal resultado estético. Poderá ocorrer dor pós-operatória, em maior ou menor grau de intensidade, por um período de tempo indeterminado e variável de paciente a paciente.";
                $data["3"] = "Por meio deste documento, eu ___________________________________________________, declaro, para todos os fins legais, que dou plena autorização ao médico (a) Dr.(a). _______________________________ CRM _________ e a sua equipe, para executar o tratamento cirúrgico designado ______________________ no meu olho ___________ e todos os procedimentos oftalmológicos que o integram e que se fizerem necessários, além da anestesia, e outras condutas médico- cirúrgicas que tal tratamento venha a exigir. Afirmo estar plenamente consciente de que a cirurgia visa, com a remoção da catarata, a melhorar a minha visão, mas que o resultado esperado pode não ser alcançado devido à existência de outras alterações oculares associadas à catarata (da córnea, da retina ou do nervo óptico) e igualmente à possibilidade da ocorrência de complicações ligadas ao próprio ato cirúrgico.";
                break;
            default :
                $data["a"] = "Como resultado da cirurgia existirá uma <strong>cicatriz</strong>, que será permanente. Contudo, todos os esforços serão feitos no sentido de encobrir e/ou diminuir a cicatriz, de forma a torná-la o menos visível possível;";
                $data["b"] = "Poderá haver <strong>inchaço</strong> (edema) na área operada que, eventualmente, pode permanecer por dias, semanas e, menos freqüentemente, por meses.";
                $data["c"] = "Poderá haver <strong>manchas</strong> (equimoses) na pele que, eventualmente, permanecerão por semanas, menos freqüentemente por meses e, raramente, serão permanentes.";
                $data["d"] = "Poderá haver a formação de uma <strong>cicatrização patológica</strong> (quelóides e cicatriz hipertrófica), dependendo das características intrínsecas e da susceptibilidade individual.";
                $data["e"] = "Poderá haver <strong>descoloração ou pigmentação</strong> cutânea nas áreas operadas por um período indeterminado de tempo. Muito raramente estas alterações poderão ser permanentes";
                $data["f"] = "Eventualmente, <strong>líquidos</strong>, sangue (hematoma) e/ou secreções (seroma) podem se       acumular na região operada, necessitando drenagem, aspiração ou reparo cirúrgico;";
                $data["g"] = "Outras condições possíveis de ocorrer são as <strong>infecções</strong>, localizadas ou não, podendo ser acompanhas da <strong>deiscência dos pontos</strong>, são geralmente controladas com uso de antibióticos e cuidados locais;";
                $data["h"] = "Poderá haver <strong>perda de sensibilidade e/ou mobilidade</strong> nas áreas operadas por um período indeterminado de tempo e que é variável de paciente a paciente;";
                $data["i"] = "Poderá ocorrer perda de vitalidade biológica na região operada, ocasionada pela redução da vascularização sangüínea, acarretando alteração na pele e, mais raramente, necrose da mesma, podendo necessitar para sua reparação de nova(s) cirurgia(s), com resultados apenas paliativos;";
                $data["j"] = "Cirurgias que envolvem os dois lados do corpo comumente acompanham assimetrias discretas ou irregularidades de superfície, não significando, no entanto, um mal resultado estético;";
                $data["k"] = "Poderá ocorrer <strong>dor pós-operatória</strong>, em maior ou menor grau de intensidade, por um período de tempo indeterminado e variável de paciente a paciente;";
                $data["l"] = "Toda cirurgia plástica pode necessitar, eventualmente, de retoques, ou pequena cirurgia complementar, para atingir um melhor resultado. Os custos hospitalares e dos outros profissionais, exceto o cirurgião, serão de obrigação do paciente.";
                $data["m"] = "Problemas vasculares, neuromusculares, trombo-embolia, reações alérgicas e até mesmo óbito, podem ocorrer; contudo, uma investigação pré-operatória exaustiva e minuciosa diminui muito estas possibilidades";
                break;
        }

        return $data;
    }

    public static function termoConsentimento($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];
        switch ($linha_cuidado) {
            case $linha_cuidado == 1:
                $data["1"] = "A Endoscopia Digestiva Alta consiste na introdução de um aparelho flexível através da boca que avaliará o esôfago, o estomago e/ou duodeno (primeira porção do intestino delgado).";
                $data["2"] = "É um exame seguro, feito geralmente em poucos minutos. Entretanto, assim como em todos os procedimentos médicos, podem ocorrer, em raras ocasiões, algumas complicações. Dentre estas destacamos a irritação vascular (flebite) no local da administração das medicações endovenosas, alergia medicamentosa, depressão respiratória, perfuração do tubo digestivo e sangramento no local da realização da biópsia ou de polipectomia. ";
                $data["3"] = "É importante que siga o preparo corretamente para que o exame seja completo, rápido e tranqüilo.";
                $data["4"] = "Você realizará a endoscopia digestiva alta apenas para diagnóstico.";
                $data["5"] = "Algumas vezes, durante a endoscopia digestiva alta, são encontrados pólipos (nódulos). Lesões precursoras do câncer.";
                $data["6"][""] = "A retirada dos pólipos com mais de 10 mm (cirurgia), durante a endoscopia digestiva alta, aumenta a risco de complicações para até 3%, sendo a mais freqüente a hemorragia mesmo após a cauterização e pode acontecer até 15 dias após o ato cirúrgico. Manifesta pela saída de sangue coaguladomisturado às fezes. A hemorragia que ocorre imediatamente após o ato cirúrgico pode ser controlada durante a cirurgia, mas mesmo assim pode sangrar novamente. <br />Após o exame siga as seguintes recomendações:";
                $data["6"]["1"] = "Dê preferência a alimentos de fácil digestão conforme aceitação e pode iniciar logo após o exame.";
                $data["6"]["2"] = "Procure ficar em casa de repouso, evitando esforços nas próximas 12 horas.";
                $data["6"]["3"] = "Não use bebidas alcoólicas nas próximas 12 horas.";
                $data["6"]["4"] = "Não tome nenhuma decisão importante nas próximas 12 horas.";
                $data["6"]["5"][""] = "Não dirija e não exerça atividades que exijam atenção ou possa feri-lo nas próximas 12 horas.<br />O que você poderá sentir após o exame?";
                $data["6"]["5"]["1"] = "Sonolência: em função dos efeitos da medicação analgésica. Apenas descanse.";
                $data["6"]["5"]["2"] = "Cólica abdominal: relacionada à injeção de ar no intestino durante o exame, e que melhoram à medida que forem eliminados. Você pode tomar 60 gotas de Simeticona após o exame e repetir após 6 horas.";
                $data["6"]["5"]["3"] = "Caso sinta dor abdominal, você também pode tomar um comprimido de Buscopan Composto a cada 6 horas (proibido para alérgicos a Dipirona).";
                $data["6"]["5"]["4"][""] = "Náuseas e vômitos: poderá ocorrer devido à medicação sedativa. Procure permanecer em repouso por mais tempo. Você pode tomar um comprimido de Plasil ou Dramin, a cada 6 horas, para controle dos sintomas<br />Procurar Hospital de referência (Pronto Socorro ou Pronto Atendimento) caso sinta: ";
                $data["6"]["5"]["4"]["1"] = "Dor abdominal forte e contínua, sem resposta às medicações acima.";
                $data["6"]["5"]["4"]["2"] = "Sangramento intestinal em maior quantidade.";
                $data["6"]["5"]["4"]["3"] = "Febre (temperatura maior que 37,7°C).  Agradecemos a sua confiança.";
                $data["7"] = "Declaro que li e compreendi todas as informações acima e autorizo a realização do procedimento de endoscopia, bem como a realização de biópsias e retiradas de pólipos durante o exame.";
                $data["8"] = "Declaro ainda, livre de qualquer coação e constrangimento, para não restar nenhuma dúvida quanto ao procedimento/exame e a minha autorização em questão, que sou conhecedor dos seus princípios, indicações, riscos, complicações e resultados, declaro ainda, bem como o médico assistente e sua equipe forneceram-me, e aos meus acompanhantes e/ou familiares, as informações referentes a cada um desses itens, de conformidade com o disposto no Código de Ética Médica. Não obstantemente, tendo ouvido, lido e aceito as explicações sobre os riscos e complicações mais comuns desta cirurgia e das chances de insucesso da mesma, declaro através de minha assinaturas aposta neste documento, o meu pleno e irrestrito consentimento para sua realização, tudo isso na presença de testemunha. ";

                break;

            case $linha_cuidado == 2:
                $data["1"] = "A Colonoscopia consiste na introdução de um aparelho flexível através do ânus para o exame interno do intestino grosso e, às vezes, da parte final do intestino delgado. O índice de sucesso ultrapassa os 95%, mas em alguns casos (menos de 5%), o exame será incompleto, como observado na literatura médica.";
                $data["2"] = "Você realizará um exame seguro e confiável, para tanto, serão administradas medicações analgésicas através da veia, antes do inicio do exame. O objetivo é diminuir a ansiedade e o desconforto, facilitando a realização do exame.";
                $data["3"] = "É importante que siga o preparo corretamente para que o exame seja completo, rápido e tranqüilo.";
                $data["4"] = "Você realizará a colonoscopia apenas para diagnóstico. Neste caso a incidência de complicações é muito baixa, variando, de acordo com a literatura, de 0,05% a 0,3%. A mais freqüente é a perfuração do intestino.Demais riscos, embora pouco comuns,podem ocorrer: falta de ar, arritmias cardíacas, reações alérgicas, aspiração do conteúdo estomacal, infecções,enfisema pleural, derrame pleural, abscesso à distância, etc...";
                $data["5"] = "Algumas vezes, durante a colonoscopia, são encontrados pólipos (nódulos). Lesões precursoras do câncer de intestino e, portanto a retirada durante o próprio procedimento evita a sua evolução para o câncer.";
                $data["6"] = "A retirada dos pólipos com mais de 10 mm (cirurgia), durante a colonoscopia, aumenta a risco de complicações para até 3%, sendo a mais freqüente a hemorragia, mesmo após a cauterização.";
                $data["7"][""] = "A hemorragia após subtração de pólipos maiores de 10 mm pode acontecer até 15 dias após o ato cirúrgico. Manifesta-se pela saída de sangue vivo ou coagulado pelo ânus, misturado ou não às fezes. A hemorragia que ocorre imediatamente após o ato cirúrgico pode ser controlada durante a cirurgia, mas mesmo assim pode haver sangramento posterior.<br/>Após o exame siga as seguintes recomendações:";
                $data["7"]["1"] = "É importante beber uma grande quantidade de líquidos para repor as perdas.";
                $data["7"]["2"] = "Procure ficar em casa de repouso, evitando esforços nas próximas 12 horas.";
                $data["7"]["3"] = "Não use bebidas alcoólicas nas próximas 12 horas.";
                $data["7"]["4"] = "Não tome nenhuma decisão importante nas próximas 12 horas.";
                $data["7"]["5"] = "Não dirija e não exerça atividades que exijam atenção ou possa feri-lo nas próximas 12 horas.";
                $data["7"]["6"][""] = "Dê preferência a alimentos de fácil digestão conforme aceitação e pode iniciar logo após o exame.<br/>O que você poderá sentir após o exame?";
                $data["7"]["6"]["1"] = "Sonolência: em função dos efeitos da medicação analgésica. Apenas descanse.";
                $data["7"]["6"]["2"] = "Cólica abdominal: relacionada à injeção de ar no intestino durante o exame, e que melhoram à medida que forem eliminados. Você pode tomar 60 gotas de Simeticona após o exame e repetir após 6 horas.";
                $data["7"]["6"]["3"] = "Caso sinta dor abdominal, você também pode tomar um comprimido de Buscopan Composto a cada 6 horas (proibido para alérgicos a Dipirona).";
                $data["7"]["6"]["4"] = "Náuseas e vômitos: poderá ocorrer devido à medicação sedativa. Procure permanecer em repouso por mais tempo. Você pode tomar um comprimido de Plasil ou Dramin, a cada 6 horas, para controle dos sintomas.";
                $data["7"]["7"][""] = "Procurar Hospital de referência (Pronto Socorro ou Pronto Atendimento) caso sinta:";
                $data["7"]["7"]["1"] = "Dor abdominal forte e contínua, sem resposta às medicações acima.";
                $data["7"]["7"]["2"] = "Sangramento intestinal em maior quantidade.";
                $data["7"]["7"]["3"] = "Febre (temperatura maior que 37,7°C). Agradecemos a sua confiança.";
                $data["8"] = "Declaro que li e compreendi todas as informações acima e autorizo a realização do procedimento de colonoscopia, bem como a realização de ";
                $data["9"] = "Declaro ainda, livre de qualquer coação e constrangimento, para não restar nenhuma dúvida quanto ao procedimento/exame e a minha autorização em questão, que sou conhecedor dos seus princípios, indicações, riscos, complicações e resultados, declaro ainda, bem como o médico assistente e sua equipe forneceram-me, e aos meus acompanhantes e/ou familiares, as informações referentes a cada um desses itens, de conformidade com o disposto no Código de Ética Médica. Não obstantemente, tendo ouvido, lido e aceito as explicações sobre os riscos e complicações mais comuns desta cirurgia e das chances de insucesso da mesma, declaro através de minha assinaturas aposta neste documento, o meu pleno e irrestrito consentimento para sua realização, tudo isso na presença de testemunha.";
                break;

        }
        return $data;
    }

    public
    static function termoConsentimentoInformadoReconhecimento($linha_cuidado, $sub_especialidade = null)
    {
        $data = [];

        switch ($linha_cuidado) {
            case $linha_cuidado == 45 and $sub_especialidade == 1 :
                $data[] = "Eu reconheço que durante o ato cirúrgico podem surgir situações ou elementos novos que não puderam ser previamente identificados e, por isso, outros procedimentos adicionais ou diferentes daqueles previamente programados possam ser necessários. Por tal razão autorizo o(a) cirurgião(ã), o(a) anestesiologista e toda sua equipe a realizarem os atos necessários condizentes com a nova situação que, eventualmente, venha a se concretizar.";
                $data[] = "Eu entendo que tanto o(a) médico(a) quanto sua equipe se obrigam unicamente a usar todos os meios técnicos e científicos à sua disposição para tentar atingir um resultado desejado que, porém, não é certo. Não sendo a Medicina uma ciência exata, fica impossível prever matematicamente um resultado para toda e qualquer prática cirúrgica, razão pela qual aceito o fato de que não me podem ser dadas garantias de resultado, tanto quanto ao percentual de melhora, como em aparência de idade ou, mesmo, a permanência dos resultados atingidos.";
                $data[] = "Eu concordo em cooperar com o médico responsável por meu tratamento até meu restabelecimento completo, fazendo a minha parte no contrato médico/paciente. Sei que devo aceitar e seguir as determinações que me forem dadas (oralmente ou por escrito), pois se não fizer a minha parte poderei comprometer o trabalho do(a) profissional, além de pôr em risco minha saúde e meu bem estar ou, ainda, ocasionar seqüelas temporárias ou permanentes.";
                $data[] = "Eu compreendo e aceito o fato de que o tabagismo, o uso de drogas e de álcool, ainda que não impeçam a realização de uma cirurgia, são fatores que podem desencadear complicações médico-cirúrgicas.";
                $data[] = "Eu autorizo o registro (foto, som, imagem, etc.) dos procedimentos necessários para a(s) cirurgia(s) proposta(s) os quais representam uma alternativa importante de  estudo e de informação científica.";
                $data[] = "Estou ciente que pode ocorrer limitação das minhas atividades cotidianas por período de tempo indeterminado.";
                $data[] = "Eu declaro que me foi fornecida a oportunidade de esclarecer todas as minhas dúvidas relativas ao ato cirúrgico ao qual, voluntariamente, irei me submeter, bem como, as formas disponíveis de anestesia, os riscos e prejuízos envolvidos e os riscos do não tratamento, razão pela qual autorizo o profissional acima designado a realizar o(s) procedimento(s) necessário(s).";
                $data[] = "Estou ciente que devo usar a medicação pós operatória conforme prescrição médica; sabendo que esta medicação não está disponível para distribuição no SUS e me disponho a comprá-la e usá-la desde o primeiro dia de pós operatório.";
                $data[] = "Declaro ainda, livre de qualquer coação e constrangimento, para não restar nenhuma dúvida quanto à cirurgia proposta e a minha autorização em questão, que sou conhecedor dos seus princípios, indicações, riscos, complicações e resultados, declaro ainda, bem como o cirurgião e sua equipe forneceram-me, e aos meus acompanhantes e/ou familiares, as informações referentes a cada um desses itens, de conformidade com o disposto no Código de Ética Médica.";
                $data[] = "Igualmente declaro estar plenamente ciente de que a cirurgia a ser realizada, em virtude da possibilidade de ocorrência de riscos e complicações, não permite ao cirurgião e à sua equipe assegurar-me a garantia expressa ou implícita de cura. Não obstantemente, tendo ouvido, lido e aceito as explicações sobre os riscos e complicações mais comuns desta cirurgia e das chances de insucesso da mesma, declaro através de minha assinaturas aposta neste documento, o meu pleno e irrestrito consentimento para sua realização, tudo isso na presença de testemunha.";
                break;
            default :
                $data[] = "<strong>Eu reconheço</strong> que durante o ato cirúrgico podem surgir situações ou elementos novos que não puderam ser previamente identificados e, por isso, <strong>outros procedimentos adicionais ou diferentes</strong> daqueles previamente programados possam ser necessários. Por tal razão autorizo o(a) cirurgião(ã), o(a) anestesiologista e toda sua equipe a realizarem os atos necessários condizentes com a nova situação que, eventualmente, venha a se concretizar.";
                $data[] = "<strong>Eu entendo</strong> que tanto o(a) médico(a) quanto sua equipe se obrigam unicamente a usar todos os meios técnicos e científicos à sua disposição para tentar atingir um resultado desejado que, porém, não é certo. Não sendo a Medicina uma ciência exata, fica impossível prever matematicamente um resultado para toda e qualquer prática cirúrgica, razão pela qual aceito o fato de que <strong>não me podem ser dadas garantias de resultado</strong>, tanto quanto ao percentual de melhora, como em aparência de idade ou, mesmo, a permanência dos resultados atingidos";
                $data[] = "<strong>Eu concordo</strong> em cooperar com o médico responsável por meu tratamento até meu restabelecimento completo, <strong>fazendo a minha parte</strong> no contrato médico/paciente. Sei que devo aceitar e seguir as determinações que me forem dadas (oralmente ou por escrito), pois se não fizer a minha parte poderei comprometer o trabalho do(a) profissional, além de pôr em risco minha saúde e meu bem estar ou, ainda, ocasionar seqüelas temporárias ou permanentes.";
                $data[] = "<strong>Eu compreendo</strong> e aceito o fato de que o <strong>tabagismo</strong>, o uso de <strong>drogas</strong> e de <strong>álcool</strong>, ainda que não impeçam a realização de uma cirurgia, são fatores que podem desencadear complicações médico-cirúrgicas.";
                $data[] = "<strong>Eu autorizo</strong> o registro (foto, som, imagem, etc.) dos procedimentos necessários para a(s) cirurgia(s) proposta(s) os quais representam uma alternativa importante de estudo e de informação científica";
                $data[] = "<strong>Estou ciente</strong> que pode ocorrer limitação das minhas atividades cotidianas por período de tempo indeterminado. Eu declaro que me foi fornecida a oportunidade de <strong>esclarecer todas as minhas dúvidas</strong> relativas ao ato cirúrgico ao qual, voluntariamente, irei me submeter, bem como, as formas disponíveis de anestesia, os riscos e prejuízos envolvidos e os riscos do não tratamento, razão pela qual <strong>autorizo</strong> o profissional acima designado a realizar o(s) procedimento(s) necessário(s).";

                if (in_array($linha_cuidado, [19, 45])) {
                    $data[] = "<strong>Estou ciente que devo usar a medicação pós operatória conforme prescrição médica; sabendo que esta medicação não está disponível para distribuição no SUS e me disponho a comprá-la e usá-la desde o primeiro dia de pós operatório.</strong>";
                }

                break;
        }

        return $data;
    }

    public
    static function checkListCirurgia($key = 1)
    {
        $data = [];

        switch ($key) {
            case 1 :
                $data[] = "Identificação";
                $data[] = "Jejum";
                $data[] = "Consentimento Informado";
                $data[] = "Procedimento";
                $data[] = "Uso de Prótese";
                break;
            case 2 :
                $data[] = "Procede";
                $data[] = "Não Procede";
                break;
            case 3 :
                $data[] = "Monitorização";
                $data[] = "Suporte de Oxigênio";
                $data[] = "Aspirador";
                $data[] = "Bisturi Elétrico (placa)";
                break;
        }

        return $data;
    }

    public
    static function getDescricaoCirurgica($key = 1)
    {
        $data = [];

        switch ($key) {
            case 1 :
                $item4[] = "TRATAMENTO DE SAFENA (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) MAGNA (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) PARVA";
                $item4[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) DISSECÇÃO + CATETERIZAÇÃO DE VEIA SAFENA MAGNA COM PASSAGEM DE FIBRA DE LASER ATÉ 3,0CM DA JUNÇÃO SAFENO-FEMORAL SOB VISÃO DE ULTRASSOM";
                $item4[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) DISSECÇÃO + CATETERIZAÇÃO DE VEIA SAFENA PARVA COM PASSAGEM DE FIBRA DE LASER ATÉ TRANSIÇÃO SUBFASCIAL  SOB VISÃO DE ULTRASSOM ABLAÇÃO DE VEIA SAFENA _______, _____ W, _____CM , __________JOULES";
                $item4[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) TRATAMENTO DE SAFENA ________________ DISTAL COM POLIDOCANOL A ....... %";
                $item4[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) PUNÇÃO DE VEIA SAFENA ________________ E TRATAMENTO COM POLIDOCANOL A ......%";
                $item4[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) LIGADURA DE SAFENA ________________";

                $data[] = "DOPPLER PRÉ OPERATÓRIO + MARCAÇÃO DE VARIZES";
                $data[] = "ANTISSEPSIA + COLOCAÇÃO DE CAMPOS ESTÉREIS";
                $data[] = "ANESTESIA LOCAL COM SOLUÇÃO ANESTESICA DE LIDOCAÍNA 2%";
                $data[] = implode("<br />", $item4);
                $data[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) FLEBECTOMIA DE VARIZES  ..................................................................................";
                $data[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)   PUNÇÃO E TRATAMENTO DE COLATERAIS VARICOSAS COM POLIDOCANOL A ..........%";
                $data[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)   PUNÇÃO E TRATAMENTO DE COLATERAIS VARICOSAS COM GLICOSE 75%";
                $data[] = "(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)   LIGADURA DE __________ PERFURANTE(S) COM ALGODÃO 2-0";
                $data[] = "CURATIVO OCLUSIVO SEGUIDO DE (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) ENFAIXAMENTO (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) COMPRESSÃO ELÁSTICA";
                break;
            case 2 :
                $data[] = "   PUNÇÃO E TRATAMENTO DE COLATERAIS VARICOSAS COM POLIDOCANOL A ..........%";
                $data[] = "   PUNÇÃO E TRATAMENTO DE COLATERAIS VARICOSAS COM GLICOSE 75% <br /><br /><br /><br /><br /><br /><br /><br />";
                break;
        }

        return $data;
    }

    public
    static function getRelacaoImpressos($linha_cuidado = null)
    {
        $data[] = "KIT COM DATA";
        $data[] = "AIH   / APAC";
        $data[] = "COLETA/ECG";
        $data[] = "AVISO";
        $data[] = "FICHA DE TRIAGEM CARIMBADO E ASSINADO PELO ENFERMEIRO";
        $data[] = "TERMO DE CONSENTIMENTO";
        if (!empty($linha_cuidado) && $linha_cuidado != 9) {
            $data[] = "SAEP<br />PRÉ/INTRA/RPA/PÓS OPERATÓRIO<br />CARIMBADO E ASSINADO PELA ENFERMAGEM";
            $data[] = "CHECK LIST CIRURGIA SEGURA";
        }
        $data[] = "FOLHA DE DÉBITO";
        $data[] = "INTEGRADOR QUÍMICO";
        $data[] = "FITA ZEBRADA";
        $data[] = "ETIQUETA STERILLENO";
        $data[] = "ANOTAÇÃO DE ENFERMAGEM";
        $data[] = "DESCRIÇÃO";
        $data[] = "PRONTUÁRIO PREENCHIDO E CARIMBADO PELO MÉDICO";
        $data[] = "RELATÓRIO DE ALTA CARIMBADO E ASSINADO PELO MÉDICO";


        return $data;
    }

    public
    static function getDescricaoCirurgicaReceita($sub_especialidade)
    {
        $data = [];

        switch ($sub_especialidade) {
            case 1 :
                $data[1] = "Assepsia e Antissepsia";
                $data[2] = "Colocação do Campo estéreis e Blefarostato";
                $data[3] = "Incisão corneana principal com bisturi 2.75";
                $data[4] = "Incisão corneana acessória com bisturi 15 graus";
                $data[5] = "Instilado Azul de Trypan";
                $data[6] = "Instilado Metilcelulose 2%";
                $data[7] = "Feito Capsulorrexe";
                $data[8] = "Facectomia com Facoemulsificação";
                $data[9] = "Aspirado córtex com caneta de irrigação e aspiração";
                $data[10] = "Implante da LIO";
                $data[11] = "Selado incisões e testado Seidel";
                $data[12] = "Instilado uma gota de colírio Vigadexa";
                $data[13] = "Retirado blefarostato e campo estéril";
                $data[14] = "Curativo";
                break;
            case 2 :
                $data[1] = "Assepsia e Antiassepsia";
                $data[2] = "Colocação de campos estéreis e blefarostato";
                $data[3] = "Exérese de pterígio";
                $data[4] = "Transplante conjuntiva";
                $data[5] = "Sutura de conjuntiva";
                $data[6] = "Pomada de antibiótico + corticoide + curativo oclusivo";
                break;
        }

        return $data;
    }

    public
    static function getHernia()
    {
        $data[1][1] = "Paciente em DDH";
        $data[1][2] = "Antissepsia";
        $data[1][3] = "Campos estéreis";
        $data[1][4] = "Incisão mediana supra umbilical";
        $data[1][5] = "Abertura por planos";
        $data[1][6] = "Identificado defeito da aponeurose e isolado conteúdo herniado";
        $data[1][7] = "Realizado fechamento do defeito da aponeurose com fio de polipropileno 2-0";
        $data[1][8] = "Hemostasia";
        $data[1][9] = "Fechamento por planos";
        $data[1][10] = "Limpeza";
        $data[1][11] = "Curativo";

        $data[2][1] = "Paciente em DDH";
        $data[2][2] = "Antissepsia com clorexidine alcoólico";
        $data[2][3] = "Colocação de campos estéreis ";
        $data[2][4] = "Incisão periumbilical";
        $data[2][5] = "Abertura por planos";
        $data[2][6] = "Liberada a cicatriz umbilical";
        $data[2][7] = "Corrigido defeito da aponeurose com fio de polipropileno 2-0";
        $data[2][8] = "Fixada cicatriz umbilical";
        $data[2][9] = "Hemostasia";
        $data[2][10] = "Fechamento da pele com nylon 4-0";
        $data[2][11] = "Limpeza";
        $data[2][12] = "Curativo";

        $data[3][1] = "Paciente em DDH";
        $data[3][2] = "Antissepsia com clorexidine alcoólico";
        $data[3][3] = "Colocação de campos estéreis";
        $data[3][4] = "Incisão periumbilical";
        $data[3][5] = "Abertura por planos";
        $data[3][6] = "Liberada a cicatriz umbilical";
        $data[3][7] = "Corrigido defeito da aponeurose com fio de polipropileno 2-0";
        $data[3][8] = "Fixada cicatriz umbilical";
        $data[3][9] = "Hemostasia";
        $data[3][10] = "Fechamento da pele com nylon 4-0";
        $data[3][11] = "Limpeza";
        $data[3][12] = "Curativo";

        return $data;
    }

    public
    static function getUrologia()
    {
        $data[1][1] = "ANTISSEPSIA E COLOCAÇÃO DE CAMPOS ESTÉREIS";
        $data[1][2] = "ANESTESIA LOCAL COM LIDOCAÍNA 2% E BUPIVACAÍNA 0,5%";
        $data[1][3] = "FRENULOPLASTIA COM CATEGUTE 4-0";
        $data[1][4] = "RESSECÇÃO DO PREPÚCIO EM EXCESSO E ANEL FIMÓTICO POR 'DUPLA-INCISÃO'";
        $data[1][5] = "REVISÃO DA HEMOSTASIA";
        $data[1][6] = "SÍNTESE COM CATEGUTE 4-0";

        $data[2][1] = "ANTISSEPSIA E COLOCAÇÃO DE CAMPOS ESTÉREIS";
        $data[2][2] = "ANESTESIA LOCAL COM LIDOCAÍNA 2% E BUPIVACAÍNA 0,5%";
        $data[2][3] = "INCISÃO ESCROTAL À DIREITA DE 1 CM";
        $data[2][4] = "DISSECÇÃO E RESSECÇÃO DE SEGMENTO DE DUCTO DEFERENTE DIREITO DE 1,5 CM";
        $data[2][5] = "LIGADURA DUPLA DOS COTOS COM FIO DE PROLENE";
        $data[2][6] = "INCISÃO ESCROTAL À ESQUERDA DE 1CM";
        $data[2][7] = "DISSECÇÃO E RESSECÇÃO DE SEGMENTO DE DUCTO DEFERENTE ESQUERDO 1,5 CM";
        $data[2][8] = "LIGADURA DUPLA DOS COTOS COM FIO DE PROLENE";

        $data[3][1] = "ANTISSEPSIA E COLOCAÇÃO DE CAMPOS ESTÉREIS";
        $data[3][2] = "ANESTESIA LOCAL COM LIDOCAÍNA 2% E BUPIVACAÍNA 0,5%";
        $data[3][3] = "IDENTIFICAÇÃO DA LESÃO";
        $data[3][4] = "RESSECÇÃO DA MESMA";
        $data[3][5] = "REVISÃO DA HEMOSTASIA";
        $data[3][6] = "SÍNTESE COM CATEGUTE 4-0";
        $data[3][7] = "LIMPEZA E CURATIVO";

        return $data;
    }

}