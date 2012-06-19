<?php

$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');


$setup->addAttribute('invoice', 'has_receipt', array(
    'input'         => 'text',
    'type'          => 'int',
    'label'         => 'Has Receipt',
    'visible'       => 1,
    'required'      => 0,
    'user_defined'  => 0,
));


$installer->endSetup();
