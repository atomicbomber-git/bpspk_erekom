<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since 5.0
* @category     Themes Header
*/

@include(c_THEMES."meta.php");

echo "
<body>
<section class=\"body\">
<header class=\"header\">
    <div class=\"logo-container\">
        <a href=\"".c_MODULE."\" class=\"logo\">
             <img src=\"".IMAGES."logo.png\" height=\"35\" alt=\"".c_APP." Admin\" />
        </a>
        <div class=\"visible-xs toggle-sidebar-left\" data-toggle-class=\"sidebar-left-opened\" data-target=\"html\" data-fire-event=\"sidebar-left-opened\">
            <i class=\"fa fa-bars\" aria-label=\"Toggle sidebar\"></i>
        </div>
    </div>
    <div class=\"header-right\">

        <span class=\"separator\"></span>

        <div id=\"userbox\" class=\"userbox\">
            <a href=\"#\" data-toggle=\"dropdown\">
                <figure class=\"profile-picture\">
                    <img src=\"".IMAGES."!logged-user.jpg\" alt=\"".U_NAME."\" class=\"img-circle\" data-lock-picture=\"".IMAGES."!logged-user.jpg\" />
                </figure>
                <div class=\"profile-info\" data-lock-name=\"".U_NAME."\" data-lock-email=\"".U_EMAIL."\">
                    <span class=\"name\">".U_NAME."</span>
                </div>

                <i class=\"fa custom-caret\"></i>
            </a>

            <div class=\"dropdown-menu\">
                <ul class=\"list-unstyled\">
                    <li class=\"divider\"></li>
                    <!--<li>
                        <a role=\"menuitem\" tabindex=\"-1\" href=\"".c_URL.$ModuleDir."users/profile.php\"><i class=\"fa fa-user\"></i> My Profile</a>
                    </li>-->
                    <li>
                        <a role=\"menuitem\" tabindex=\"-1\" href=\"?lock\"><i class=\"fa fa-lock\"></i> Lock Screen</a>
                    </li>
                    <li>
                        <a role=\"menuitem\" tabindex=\"-1\" href=\"?keluar\"><i class=\"fa fa-power-off\"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
";

@include (c_THEMES."menu-admin.php");

?>