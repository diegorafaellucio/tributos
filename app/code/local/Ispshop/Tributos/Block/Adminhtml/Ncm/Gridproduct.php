<?php

class Ispshop_Tributos_Block_Adminhtml_Ncm_Gridproduct extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();

        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('ispshop_tributos_ncm_grid_product');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass() {
        // This is the model we are using for the grid
        return 'ispshop_tributos/ncm_collection';
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

        $this->addColumn('id_monitoramento_ncm', array(
            'header' => $this->__('ID NCM'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id_monitoramento_ncm'
                )
        );


        $this->addColumn('product_id', array(
            'header' => $this->__('Produto'),
            'index' => 'product_id'
                )
        );

        $this->addColumn('region_id', array(
            'header' => $this->__('Região'),
            'index' => 'region_id',
                )
        );

        $this->addColumn('observacao', array(
            'header' => $this->__('Observação'),
            'index' => 'observacao'
                )
        );

        $this->addColumn('aliquota_icms_interno', array(
            'header' => $this->__('ICMS Interno'),
            'index' => 'aliquota_icms_interno'
                )
        );

        $this->addColumn('aliquota_icms_interestadual', array(
            'header' => $this->__('ICMS Externo'),
            'index' => 'aliquota_icms_interestadual'
                )
        );

        $this->addColumn('mva', array(
            'header' => $this->__('MVA'),
            'index' => 'mva'
                )
        );

        $this->addColumn('mva_ajustada', array(
            'header' => $this->__('MVA Ajustada'),
            'index' => 'mva_ajustada'
                )
        );
        $this->addColumn('mva_ajustada_4', array(
            'header' => $this->__('MVA Ajustada 4'),
            'index' => 'mva_ajustada_4'
                )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        // This is where our row data will link to

        $canEditNcm = Mage::getSingleton('admin/session')->isAllowed('admin/catalog/ispshop_tributos_ncm/pode_editar');

        if ($canEditNcm) {
            return $this->getUrl('*/ncm/edit', array('id' => $row->getId()));
        } else {
            return;
        }
    }

}
