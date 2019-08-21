<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     pagination
*/

 // total page count calculation
    $pages = ((int) ceil($total / $rpp));

    // if it's an invalid page request
    if ($current < 1) {
        return;
    } elseif ($current > $pages) {
        return;
    }
    if ($pages > 1) {
?>
<ul class="<?php echo implode(' ', $classes) ?>">
<?php
        /**
         * Previous Link
         */

        // anchor classes and target
        $classes = array('copy', 'previous');
        $params = $get;
        $params[$key] = ($current - 1);
        $href = ($target) . '?' . http_build_query($params);
        $params[$key] = 1;
        $href_first = ($target) . '?' . http_build_query($params);
        $href = preg_replace(
            array('/=$/', '/=&/'),
            array('', '&'),
            $href
        );
        if ($current === 1) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
    <li class="<?php echo implode(' ', $classes) ?>"><a href="<?php echo ($href_first) ?>"><?php echo "First"; ?></a></li>
    <li class="<?php echo implode(' ', $classes) ?>"><a href="<?php echo ($href) ?>"><?php echo ($previous) ?></a></li>
<?php
        /**
         * if this isn't a clean output for pagination (eg. show numerical
         * links)
         */
        if (!$clean) {

            /**
             * Calculates the number of leading page crumbs based on the minimum
             *     and maximum possible leading pages.
             */
            $max = min($pages, $crumbs);
            $limit = ((int) floor($max / 2));
            $leading = $limit;
            //echo "max-".$max." limit-".$limit." leading-".$leading;
            for ($x = 0; $x < $limit; ++$x) {
                if ($current == ($x + 1)) {
                    $leading = $x;
                    break;
                }
            }
            for ($x = $pages - $limit; $x < $pages; ++$x) {
                if ($current == ($x + 1)) {
                    $leading = $max - ($pages - $x);
                    break;
                }
            }

            // calculate trailing crumb count based on inverse of leading
            $trailing = $max - $leading - 1;

            // generate/render leading crumbs
            for ($x = 0; $x < $leading; ++$x) {

                // class/href setup
                $params = $get;
                $params[$key] = ($current + $x - $leading);
                $href = ($target) . '?' . http_build_query($params);
                $href = preg_replace(
                    array('/=$/', '/=&/'),
                    array('', '&'),
                    $href
                );
?>
    <li class="number"><a data-pagenumber="<?php echo ($current + $x - $leading) ?>" href="<?php echo ($href) ?>"><?php echo ($current + $x - $leading) ?></a></li>
<?php
            }

            // print current page
?>
    <li class="number active"><a data-pagenumber="<?php echo ($current) ?>" href="#"><?php echo ($current) ?></a></li>
<?php
            // generate/render trailing crumbs
            for ($x = 0; $x < $trailing; ++$x) {

                // class/href setup
                $params = $get;
                $params[$key] = ($current + $x + 1);
                $href = ($target) . '?' . http_build_query($params);
                $href = preg_replace(
                    array('/=$/', '/=&/'),
                    array('', '&'),
                    $href
                );
?>
    <li class="number"><a data-pagenumber="<?php echo ($current + $x + 1) ?>" href="<?php echo ($href) ?>"><?php echo ($current + $x + 1) ?></a></li>
<?php
            }
        }

        /**
         * Next Link
         */

        // anchor classes and target
        $classes = array('copy', 'next');
        $params = $get;
        $params[$key] = ($current + 1);
        $href = ($target) . '?' . http_build_query($params);
        $params[$key] = $pages;
        $href_last = ($target) . '?' . http_build_query($params);
        $href = preg_replace(
            array('/=$/', '/=&/'),
            array('', '&'),
            $href
        );
        if ($current === $pages) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
    <li class="<?php echo implode(' ', $classes) ?>"><a href="<?php echo ($href) ?>"><?php echo ($next) ?></a></li>
    <li class="<?php echo implode(' ', $classes) ?>"><a href="<?php echo ($href_last) ?>"><?php echo "Last"; ?></a></li>
</ul>
<?php
    }
