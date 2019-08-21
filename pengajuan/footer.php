<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since 5.0
* @category     Themes Footer
*/

if (USER) {
echo "
</body>
";
}

ITEM_FOOT($ITEM_FOOT);
SCRIPT_FOOT($SCRIPT_FOOT);

//if($sql) $sql -> db_Close();
?>