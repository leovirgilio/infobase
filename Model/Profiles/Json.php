<?php
namespace LeoVirgilio\Infobase\Model\Profiles;

use LeoVirgilio\Infobase\Model\ProfileAbstract;

class Json extends ProfileAbstract
{
    /**
     * @return array
     */
    public function read(): array
    {
        // Faz um split do caminho do arquivo
        $this->_explodePath();
        // Prepara a saída do conteúdo do arquivo
        $this->_output();

        return json_decode($this->_content, true);;
    }
}
