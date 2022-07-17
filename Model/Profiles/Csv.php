<?php

namespace LeoVirgilio\Infobase\Model\Profiles;

use LeoVirgilio\Infobase\Model\ProfileAbstract;

class Csv extends ProfileAbstract
{

    /**
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function _output(): void
    {
        $directoryRead = $this->_directoryReadFactory->create($this->_relativePath);
        $file = $directoryRead->openFile($this->_fileName);

        $result = [];
        while (($row = $file->readCsv()) !== false) {
            if (is_array($row) && count($row) > 1) {
                $result[] = $row;
            }
        }

        $this->_content = json_encode($result);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    public function read(): array
    {
        // Faz um split do caminho do arquivo
        $this->_explodePath();
        // Prepara a saída do conteúdo do arquivo
        $this->_output();

        $result = [];

        $data = json_decode($this->_content);
        $header = array_shift($data);
        foreach ($data as $row) {
            $item = [];
            foreach ($row as $key => $value) {
                $item[$header[$key]] = $value;
            }

            $result[] = $item;
        }

        return $result;
    }
}

