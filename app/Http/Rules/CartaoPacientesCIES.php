<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 04/09/2018
 * Time: 10:27
 */

namespace App\Http\Rules;


use App\Http\Helpers\Mask;
use App\Http\Helpers\Util;
use App\Pacientes;

class CartaoPacientesCIES
{
    protected $paciente;
    protected $cartao;

    /**
     * @param Pacientes $paciente
     * @return bool
     */
    public function gerarNumeroCartao(Pacientes $paciente)
    {
        $this->paciente = $paciente;

        try {
            $this->validaCartao();
            $this->gerarCartao();

            return $this->cartao;
        } catch (\Exception $e) {

        }

        return false;
    }

    /**
     * @param $cartao
     * @throws \Exception
     */
    public function getPacienteByCartao($cartao)
    {
            $_cartao = Util::somenteNumeros($cartao);

            if (strlen($_cartao) != 16) {
                throw new \Exception("Cartão inválido!");
            }

            $cpf = $this->getCPFByCartao($cartao);

            return Pacientes::getCPF($cpf);
    }

    /**
     * @param null $cpf
     * @param null $data_cadastro
     * @return bool
     * @throws \Exception
     */
    public function validaCartao()
    {
        $cpf = Util::somenteNumeros($this->paciente->cpf);
        if (strlen($cpf) < 10) {
            throw new \Exception("CPF inválido!");
        }

        return true;
    }

    /**
     *
     */
    private function gerarCartao()
    {
        $cpf = str_pad(Util::somenteNumeros($this->paciente->cpf), 11, "0", STR_PAD_LEFT);
        $data_cadastro = substr(Util::somenteNumeros($this->paciente->created_at), 4, 5);

        $this->cartao = Mask::CIES($this->mountCard($cpf . $data_cadastro));
    }

    private function mountCard($data)
    {
        $data = substr(Util::somenteNumeros($data), 0, 16);

        $d[] = substr($data, 0, 2);
        $d[] = substr($data, 2, 2);
        $d[] = substr($data, 4, 2);
        $d[] = substr($data, 6, 2);
        $d[] = substr($data, 8, 2);
        $d[] = substr($data, 10, 2);
        $d[] = substr($data, 12, 2);
        $d[] = substr($data, 14, 2);

        return implode("", array_reverse($d));
    }

    private function getCPFByCartao($cartao){
        return substr($this->mountCard($cartao), 0, 11);
    }
    



}