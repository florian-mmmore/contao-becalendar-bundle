<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 26.01.18
 * Time: 09:18
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\dca;


$table = 'tl_user';

$GLOBALS['TL_DCA'][$table]['palettes']['group'] = str_replace('email', 'email,dreibein_color', $GLOBALS['TL_DCA'][$table]['palettes']['group']);

$GLOBALS['TL_DCA'][$table]['fields']['dreibein_color'] = [
    'label'     => &$GLOBALS['TL_LANG'][$table]['dreibein_color'],
    'exclude'   => false,
    'inputType' => 'text',
    'eval'      => ['mandatory'=>true, 'maxlength'=>6, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
    'sql'       => "varchar(6) NOT NULL default ''"
];

class tl_dreibein_user
{

}