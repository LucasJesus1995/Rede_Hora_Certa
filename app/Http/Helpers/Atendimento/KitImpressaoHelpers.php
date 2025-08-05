<?php


namespace App\Http\Helpers\Atendimento;


class KitImpressaoHelpers
{

    public static function getKitEspecialidade($especialidade, $sub_especialidade = null)
    {
        $file = [];
        switch ($especialidade) {
//            case 9:
//                $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                $file[] = "elements.layout.kit.cirurgico.em-branco";
//                $file[] = "elements.layout.kit.especialidades.escleroterapia.termo-consentimento";
//                $file[] = "elements.layout.kit.cirurgico.em-branco";
//                $file[] = "elements.layout.kit.especialidades.escleroterapia.ficha-atendimento-medico";
//                $file[] = "elements.layout.kit.cirurgico.descricao-cirurgica";
//                $file[] = "elements.layout.kit.cirurgico.alta-medica";
//                $file[] = "elements.layout.kit.cirurgico.relacao-impressos";
//                $file[] = "elements.layout.kit.cirurgico.folha-debito";
//                $file[] = "elements.layout.kit.cirurgico.em-branco";
//                $file[] = "elements.layout.kit.cirurgico.receita";
//                $file[] = "elements.layout.kit.cirurgico.em-branco";
//                $file[] = "elements.layout.kit.cirurgico.alta-medica-paciente";
//
//                break;
//            case 45:
//                switch ($sub_especialidade) {
//                    case 1:
//                        $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                        $file[] = "elements.layout.kit.cirurgico.planejamento-assistencia-enfermagem";
//                        $file[] = "elements.layout.kit.cirurgico.termo-consentimento";
//                        $file[] = "elements.layout.kit.especialidades.escleroterapia.ficha-atendimento-medico";
//                        $file[] = "elements.layout.kit.cirurgico.check-list";
//                        $file[] = "elements.layout.kit.cirurgico.descricao-cirurgica";
//                        $file[] = "elements.layout.kit.cirurgico.alta-medica";
//                        $file[] = "elements.layout.kit.cirurgico.relacao-impressos";
//                        $file[] = "elements.layout.kit.cirurgico.folha-debito";
//                        $file[] = "elements.layout.kit.cirurgico.receita";
//                        $file[] = "elements.layout.kit.cirurgico.em-branco";
//                        $file[] = "elements.layout.kit.cirurgico.alta-medica-paciente";
//
//                        break;
//                    case 2:
//                        $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                        $file[] = "elements.layout.kit.cirurgico.planejamento-assistencia-enfermagem";
//                        $file[] = "elements.layout.kit.cirurgico.termo-consentimento.pterigio";
//                        $file[] = "elements.layout.kit.especialidades.escleroterapia.ficha-atendimento-medico";
//                        $file[] = "elements.layout.kit.cirurgico.check-list";
//                        $file[] = "elements.layout.kit.cirurgico.descricao-cirurgica";
//                        $file[] = "elements.layout.kit.cirurgico.alta-medica";
//                        $file[] = "elements.layout.kit.cirurgico.relacao-impressos";
//                        $file[] = "elements.layout.kit.cirurgico.folha-debito";
//                        $file[] = "elements.layout.kit.cirurgico.receita";
//                        $file[] = "elements.layout.kit.cirurgico.em-branco";
//                        $file[] = "elements.layout.kit.cirurgico.alta-medica-paciente";
//                        break;
//                    case 3:
//                        $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                        $file[] = "elements.layout.kit.cirurgico.em-branco";
//                        $file[] = "elements.layout.kit.cirurgico.termo-consentimento-yag-laser";
//                        $file[] = "elements.layout.kit.cirurgico.em-branco";
//                        $file[] = "elements.layout.kit.yag-laser";
//                        break;
//                    case 10:
//                        $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                        $file[] = "elements.layout.kit.cirurgico.avaliacao-pos-operatoria";
//                        $file[] = "elements.layout.kit.cirurgico.alta-medica";
//                        $file[] = "elements.layout.kit.cirurgico.relacao-impressos";
//
//                        break;
//                }
//                break;
            case 47:
                $file[] = "kit-impressao.cirurgico.default.ficha-acolhimento";
                $file[] = "kit-impressao.em-branco";
                $file[] = "kit-impressao.cirurgico.urologia.termo-consentimento";
                $file[] = "kit-impressao.cirurgico.default.planejamento-assistencia-enfermagem";
                $file[] = "kit-impressao.cirurgico.escleroterapia.ficha-atendimento-medico";
                $file[] = "elements.layout.kit.cirurgico.check-list";
                $file[] = "kit-impressao.cirurgico.default.descricao-cirurgica";
                $file[] = "kit-impressao.cirurgico.default.pos-operatorio";
                $file[] = "kit-impressao.cirurgico.default.alta-medica";
                $file[] = "kit-impressao.cirurgico.default.relacao-impressos";
                $file[] = "kit-impressao.cirurgico.default.folha-debito";
                $file[] = "kit-impressao.em-branco";
                $file[] = "kit-impressao.cirurgico.default.receita";
                $file[] = "kit-impressao.em-branco";
                $file[] = "kit-impressao.cirurgico.default.alta-medica-paciente";
                break;
//            case 23:
//                $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                $file[] = "elements.layout.kit.cirurgico.em-branco";
//                $file[] = "elements.layout.kit.tomografia";
//                $file[] = "elements.layout.kit.tomografia-termo-consentimento";
//                break;
//            case 27:
//                $file[] = "elements.layout.kit.especialidades.ficha-acolhimento";
//                $file[] = "elements.layout.kit.cirurgico.em-branco";
//                $file[] = "elements.layout.kit.ressonancia";
//                $file[] = "elements.layout.kit.ressonancia-termo-consentimento";
//                break;
        }


        return $file;
    }


}