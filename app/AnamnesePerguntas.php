<?php

namespace App;

use App\Http\Helpers\Anamnese;
use App\Http\Helpers\Anmense;
use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;

class AnamnesePerguntas extends Model
{
    protected $table = 'anamnense_perguntas';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

        });
    }

    public static function Questionario($type)
    {
        $ask = array();
        switch ($type) {
            case 1:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::QuestionarioEspecifico())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 2:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::AcolhimentoEnfermagem())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 3:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::EndoscopiaEnfermagem())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 4:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::Intercorrencia())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 5:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::CheckList())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 6:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::SinaisVitais())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 7:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::Rasteabilidade())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 8:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::DiagnosticoEnfermagem())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 9:
                $ask = AnamnesePerguntas::whereIn('id', Anamnese::PerguntasAnamneseMedicaUltrasom())->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 10: #ficha de acolhimento
                $ask = AnamnesePerguntas::whereIn('id', [270, 271, 272, 273, 274])->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 11: #ficha de acolhimento
                $ask = AnamnesePerguntas::whereIn('id', [1, 14, 10, 12, 13, 18])->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 12: #ficha de acolhimento
                $ask = AnamnesePerguntas::whereIn('id', [1, 14, 10, 11, 252, 27, 21, 17, 15, 16, 18, 265, 266, 267, 26])->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 13: #ficha de acolhimento ERGOMÉTRICO
                $ask = AnamnesePerguntas::whereIn('id', [1, 5, 10, 14, 11, 12, 13, 252, 27,  17, 21, 16, 276, 269, 265, 266, 267, 26])->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 23:
                $ask = AnamnesePerguntas::whereIn('id', [1, 10, 12, 13, 18])->orderBy('ordem', 'asc')->get()->toArray();
                break;
            case 27:
                $ask = AnamnesePerguntas::whereIn('id', [1, 10, 12, 13, 18])->orderBy('ordem', 'asc')->get()->toArray();
                break;
        }
        return $ask;
    }


    public static function FormularioVascular($type = 1)
    {
        $data = null;

        switch ($type) {
            case 1:
                $data[] = "<b>Cirurgia de varizes</b> em membro inferior <span class='quadrado'>&nbsp;</span> direito <span class='quadrado'>&nbsp;</span> esquerdo.";
                $data[] = Util::StrPadRight("<b>TVP</b> <span class='quadrado'>&nbsp;</span> direito <span class='quadrado'>&nbsp;</span> esquerdo; Está em uso anticoagulante&nbsp;", 194, "_");
                $data[] = Util::StrPadRight("<b>HAS</b> anti-hipertensivos&nbsp;", 121, "_");
                $data[] = Util::StrPadRight("<b>Diabetes</b> hipoglicemiantes&nbsp;", 121, "_");
                $data[] = Util::StrPadRight("<b>IAM</b>&nbsp;", 117, "_");
                $data[] = Util::StrPadRight("<b>AVC</b>&nbsp;", 116, "_");
                $data[] = Util::StrPadRight("<b>Outros</b>&nbsp;", 117, "_");
                break;
            case 2:
                $data[] = "CEAP 0 sem varizes";
                $data[] = "CEAP 1 telangiectasias/reticulares";
                $data[] = "CEAP 2 varizes";
                $data[] = "CEAP 3 edema";
                $data[] = "CEAP 4 alterações de pele";
                $data[] = "CEAP 5 úlcera cicatrizada";
                $data[] = "CEAP 6 úlcera ativa";
                break;
            case 3:
                $data['Doenças venosas'][] = "I82";
                $data['Doenças venosas'][] = "I83";
                $data['Doenças venosas'][] = "I83.9";
                $data['Doenças venosas'][] = "I87";
                $data['Doenças venosas'][] = "I87.2";
                $data['Doenças venosas'][] = "I87.9";

                $data['Doenças arteriais'][] = "I70.0";
                $data['Doenças arteriais'][] = "I70.2";
                $data['Doenças arteriais'][] = "I70.8";
                $data['Doenças arteriais'][] = "I74.0";
                $data['Doenças arteriais'][] = "I74.3";
                $data['Doenças arteriais'][] = null;
                break;
        }


        return $data;
    }

    public static function FormularioRessonancia($type = 1)
    {
        switch ($type) {
            case 1:
                $data[] = "Usa Marca passo cardíaco ou fios de marca passo?";
                $data[] = "Usa Clipes para aneurisma cerebral?";
                $data[] = "Tem Neuroestimuladores implantados na coluna espinhal?";
                $data[] = "Usa Implante cóclea, cirurgia de ouvido?";
                $data[] = "Usa Dentadura ou prótese removíveis?";
                $data[] = "Usa Implantes dentárias ou magnéticos (próteses)?";
                $data[] = "Tem Tatuagem/maquiagem definitiva?";
                $data[] = "Tem Piercing, ponto de acupuntura ou sutura metálica?";
                $data[] = "Tem algum fragmento metálico (projétil/arma de fogo). <br>Se sim, qual? _______________________________________________";
                $data[] = "Tem algum tipo partículas de metal nos olhos? (Chumbo de ca&ccedil;a, limalhas, estilha&ccedil;os, outros...)";
                $data[] = "Qualquer outro objeto ou artefato metálico?";
                $data[] = "Sofre de alguma doen&ccedil;a crônica? (Diabetes, reumatismos, doen&ccedil;as do fígado, rins ou tireoide, Hipertensão, anemia, outras. <br />Qual?________________________________________";
                $data[] = "Tem claustrofobia?";
                $data[] = "Tem stents coronários?";
                $data[] = "Sofre de desmaios ou de epilepsia?";
                $data[] = "Usa algum tipo de emplastro com medica&ccedil;ão?";
                $data[] = "Tem insuficiência renal? Se sim faz hemodiálise?";
                break;
            case 2:
                $data[] = "Existe possibilidade de estar grávida?";
                $data[] = "Esta amamentando ou deu a luz recentemente?";
                $data[] = "Utiliza anticoncepcional oral ou DIU?";
                break;
            case 3:
                $data[] = "Já realizou biópsia de próstata?";
                break;
            case 4:
                $data[] = "Caso haja necessidade do contraste você permite sua utiliza&ccedil;ão?";
                $data[] = "Caso haja necessidade do gel vaginal você permite sua utiliza&ccedil;ão?";
                break;
            case 5:
                $data[] = "História de alguma rea&ccedil;ão ao contraste da ressonância?";
                $data[] = "Historia de alguma outra alergia com necessidade de tratamento?";
                $data[] = "Tem asma?";
                break;
            case 6:
                $data[] = "Algum problema nos rins? Qual? ______________________________________________________________";
                $data[] = "Dosagem mais recente de creatina sérica (no sangue) Valor: ________________ Data _____/_____/________";
                $data[] = "Há quanto tempo está em jejum? ______________________________________________________________";
                break;
        }

        return $data;
    }

    public static function FormularioTomografia($type)
    {
        switch ($type) {
            case 1:
                $data[] = "História de alguma rea&ccedil;ão ao contraste utilizado na Tomografia Computadorizada?";
                $data[] = "Já realizou algum exame como Colangiografia Endovenosa, Urografia Excretora, Arteriografia e Tomografia Computadorizada? <br />Qual ou Quais? _________________________________________________________________________________________";
                $data[] = "História de alguma outra alergia com necessidade de tratamento?";
                $data[] = "História de alguma rea&ccedil;ão alérgica aos Frutos do Mar? Ou a Iodo?";
                $data[] = "Utilizou contraste Iodado nas últimas horas?";
                $data[] = "Tem Asma, Bronquite e Rinite? Qual? ________________________________________________________________________<br />Há quanto tempo? ___________   Qual tratamento? _____________________________________________________________";
                $data[] = "É diabético? Utiliza alguns destes medicamentos: METFORMINA, GLIFAGE, GLUCOFORMIN, GLUCOPHAGE, MEGUANIN ou DIMEFOR?<br/>________________________________________________________________________________________________________________";
                $data[] = "Sofre de alguma doen&ccedil;a crônica? <br />Como: DIABETES, REUMATISMO, DOEN&ccedil;AS DO FIGADO, RINS ou TIREOIDE, HIPERTEN&ccedil;ÃO, ANEMIA e OUTRAS. Qual? ________________________________________________________________________________________________________________";
                $data[] = "Algum problema nos rins? <br />Qual? __________________________________________________________________________________________________________";
                break;
            case 2:
                $data[] = "Dosagem mais recente de creatina sérica (no sangue) se nefropata?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Valor: _______________   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Data:  _____/_____/________";
                $data[] = "Há quanto tempo está em jejum? ____________________________________________________________________________________";
                break;
            case 3:
                $data[] = "Existe a possibilidade de estar grávida?";
                $data[] = "Esta amamentando ou deu à luz recentemente";
                break;
            case 4:
                $data[] = "Bloqueador adrenérgico. Ex.: Atenolol, Propanolol";
                $data[] = "Bloqueador de canal de cálcio, Nifediplina ou nicardipina. Ex.: Adalat";
                $data[] = "Hipoglicemiantes (remédio para diabetes) Ex.: Metformina, Glucoformim, Glifage, Glucovance, Amaryl, Dimefor, Diaformim, Glicomet, Debel, Actos, Formet.";
                $data[] = "Digoxina, Digitoxina";
                $data[] = "Outros. Citar: _____________________________________________________________________________________________________________";
                break;
            case 5:
                $data[] = "Caso haja necessidade do contraste você permite sua utiliza&ccedil;ão";
                break;
            case 6:
                $data[] = "É fumante? <br />Se sim,  quantos ma&ccedil;os de cigarro por dia?___________________";
                $data[] = "Já passou por algum tipo de cirurgia? <br />Se sim, qual e há quanto tempo? __________________________";
                $data[] = "Realizou quimioterapia ou radioterapia? <br />Quando foi a última sessão?";
                break;
        }

        return $data;
    }

    public static function FormularioMamografia($type)
    {
        switch ($type) {
            case 1:
                $data[] = "Drenagem de abscesso";
                $data[] = "Retirada de nódulo";
                $data[] = "Mamoplastia redutora";
                $data[] = "Prótese mamária";
                $data[] = "Mastectomia parcial";
                $data[] = "Mastectomia total";
                break;
            case 2:
                $data[] = "Histórico familiar";
                $data[] = "Reposi&ccedil;ão hormonal";
                $data[] = "Secre&ccedil;ão do mamilo";
                break;
        }

        return $data;
    }

    public static function FormularioOftamologia($type)
    {
        switch ($type) {
            case 1:
                $data[] = "PALPEBRAS";
                $data[] = "CONJUNTIVA";
                $data[] = "CÓRNEA";
                $data[] = "IRIS/CÂMARA ANT";
                $data[] = "CRISTALINO";
                break;
        }

        return $data;
    }

    public static function FormularioColoproctologia($type)
    {
        switch ($type) {
            case 1:
                $data["K12"] = "Estomatite";
                $data["K51"] = "Colite ulcerativa";
                $data["K20"] = "Esofagite";
                $data["K52.9"] = "Diarréia crônica não infecciosa";
                $data["K21"] = "Doença do refluxo gastroesofágico";
                $data["K57.9"] = "Diverticulite, diverticulose";
                $data["K25"] = "Úlcera gástrica	";
                $data["K58"] = "Sindrome do cólon irritável	";
                $data["K26"] = "Úlcera duodenal	";
                $data["K60"] = "Fissura e fístula anorretal	";
                $data["K29"] = "Gastrite e duodenite";
                $data["K70.3"] = "Cirrose alcoólica";
                $data["K30"] = "Dispepsia";
                $data["K74"] = "Cirrose fibrose hepáticas";
                $data["K40"] = "Hérnia inguinal";
                $data["K80"] = "Colecistolitíase";
                $data["K42"] = "Hérnia umbilical";
                $data["K80.5"] = "Cólica biliar";
                $data["K44.9"] = "Hérnia de hiato";
                $data["K81"] = "Colecistite";
                $data["C15-26"] = "Neoplasia maligna de órgãos digestivos";
                break;
        }

        asort($data);
    }

    public static function FormularioUrologia($type)
    {
        switch ($type) {
            case 1:
                $data[] = "HASP";
                $data[] = "DMP";
                $data[] = "IAM";
                $data[] = "AVC";
                break;
            case 2:
                $data[] = "Anti hipertensivos";
                $data[] = "Hipoglicemiantes";
                $data[] = "Anticoagulantes";
                $data[] = "Antiagregantes";
                $data[] = "Anticovulsivantes";
                break;
        }

        asort($data);

        return $data;
    }

}
