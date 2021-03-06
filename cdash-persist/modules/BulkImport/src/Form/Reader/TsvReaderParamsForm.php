<?php declare(strict_types=1);

namespace BulkImport\Form\Reader;

use Laminas\Form\Element;

class TsvReaderParamsForm extends SpreadsheetReaderConfigForm
{
    public function init(): void
    {
        // Set binary content encoding
        $this->setAttribute('enctype', 'multipart/form-data');

        $this
            ->add([
                'name' => 'file',
                'type' => Element\File::class,
                'options' => [
                    'label' => 'File (tsv)', // @translate
                ],
                'attributes' => [
                    'id' => 'file',
                    'required' => true,
                    // Some servers doesn't detect tsv, so add csv too.
                    'accept' => 'text/tab-separated-values,text/csv,application/csv,csv,tsv',
                ],
            ]);

        parent::init();
    }
}
