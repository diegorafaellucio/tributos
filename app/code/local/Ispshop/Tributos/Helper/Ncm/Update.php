<?php

class Ispshop_Tributos_Helper_Ncm_Update extends Mage_Core_Helper_Data {

    public function getUpdatedFields($item, $data) {

        $updates = array();

        $databaseData = $item->getData();
        unset($databaseData['id']);

        $i = 0;

        foreach ($databaseData as $key => $value) {
            if (($i == 5) || ($i == 8) || ($i == 9) || ($i == 15) || ($i == 16) || ($i == 17) || ($i == 22) || ($i == 23) || ($i == 24) || ($i == 28) || ($i == 29) || ($i == 30)) {
                $dataValue = number_format(floatval($data[$i]), 2, '.', '');
            } else {
                $dataValue = $data[$i];
            }

            if ($dataValue != $value) {
                $updates[] = $key;
            }
            $i++;
        }
        return $updates;
    }

    public function getUpdatedFieldsToString($updatedFields) {
        $updatedFieldsString = null;
        
        foreach ($updatedFields as $key => $value) {
            
            if ($key == 0){
            $updatedFieldsString .= $value;
            }else{
            $updatedFieldsString .= ', '.$value;
            }
        }

        return $updatedFieldsString;
    }

}
