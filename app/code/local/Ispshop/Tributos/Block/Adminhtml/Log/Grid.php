<?php

class Ispshop_Tributos_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();

        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('ispshop_tributos_log_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass() {
        // This is the model we are using for the grid
        return 'ispshop_tributos/log_collection';
    }

    protected function _prepareCollection() {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        // Add the columns that should appear in the grid
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id'
                )
        );

        $this->addColumn('product_id', array(
            'header' => $this->__('ID do Produto'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'product_id'
                )
        );
        $this->addColumn('ncm', array(
            'header' => $this->__('NCM'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'ncm'
                )
        );


        $this->addColumn('updated_fields', array(
            'header' => $this->__('Campos atualizados'),
            'index' => 'updated_fields'
                )
        );

        $this->addColumn('country_region', array(
            'header' => $this->__('Estado'),
            'index' => 'country_region',
                )
        );

        $this->addColumn('type', array(
            'header' => $this->__('Tipo do Log'),
            'index' => 'type'
                )
        );

        $this->addColumn('responsable', array(
            'header' => $this->__('Atualizado Por'),
            'index' => 'responsable'
                )
        );
        
        $this->addColumn('created_at', array(
            'header' => $this->__('Atualização realizada em:'),
            'index' => 'created_at'
                )
        );
        

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        // This is where our row data will link to


        if (($row->getProductId() != '0')) {
            return $this->getUrl('*/catalog_product/edit', array('id' => $row->getProductId()));
        } else {
            return;
        }
    }

}
