<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="/system/templates/img/favicon.ico" type="image/x-icon"/>
        <title><?php echo ucfirst($w->currentModule()); ?><?php echo !empty($title) ? ' - ' . $title : ''; ?></title>        
        <?php
        CmfiveStyleComponentRegister::registerComponent('app', new CmfiveStyleComponent("/system/templates/base/dist/app.css"));
        CmfiveScriptComponentRegister::registerComponent('app', new CmfiveScriptComponent("/system/templates/base/dist/app.js"));

        // // Print registered vue component links
        /** @var Web $w */
        // $w->loadVueComponents();

        $w->outputStyles();
        ?>
    </head>
    <body>
        <div id="app">
            <div id="offscreen-menu" :class="{'active': showSidemenu}">
                <div class="d-flex flex-row justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <a class="nav-link" href="/">
                            <img class="nav" width="24px" height="24px" src="/system/templates/img/cmfive_V_logo.png" />
                        </a>
                        <h5 class="d-flex flex-row mt-1 mb-1">Menu</h5>
                    </div>
                    <a class="nav-link pt-0 pb-1" href="/"><i class="bi bi-x" style="font-size: 24px;"></i></a>
                </div>
                <nav class="navbar navbar-expand navbar-light bg-light justify-content-start">
                    <a class="nav-link nav-icon" href="/"><i class="bi bi-house-fill"></i></a>
                    <a class="nav-link nav-icon" href="/"><i class="bi bi-star-fill"></i></a>
                    <a class="nav-link nav-icon" href="/"><i class="bi bi-person-fill"></i></a>
                    <a class="nav-link nav-icon" href="/"><i class="bi bi-question-circle-fill"></i></a>
                    <a class="nav-link nav-icon" href="/"><i class="bi bi-search"></i></a>
                </nav>
            </div>
            <div id="content">
                <div class="container-fluid" id="navbar">
                    <nav class="container navbar navbar-expand-lg navbar-light bg-light">
                        <div class="container-fluid">
                            <a class="nav-link nav-icon" :class="{'active': showSidemenu}" @click="toggleMenu()" href="#"><i class="bi bi-list"></i></a>
                            <a class="nav-link nav-icon" href="/"><i class="bi bi-house-fill"></i></a>
                            <a class="nav-link nav-icon" href="/"><i class="bi bi-star-fill"></i></a>
                            <a class="nav-link nav-icon" href="/"><i class="bi bi-person-fill"></i></a>
                            <a class="nav-link nav-icon" href="/"><i class="bi bi-question-circle-fill"></i></a>
                            <a class="nav-link nav-icon" href="/"><i class="bi bi-search"></i></a>
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <?php foreach ($w->modules() as $module) :
                                    // Check if config is set to display on topmenu
                                    if (Config::get("{$module}.topmenu") && Config::get("{$module}.active")) :
                                        // Check for navigation
                                        $service_module = ucfirst($module);
                                        $menu_link = method_exists($w->$service_module, "menuLink") ? $w->$service_module->menuLink() : $w->menuLink($module, is_bool(Config::get("{$module}.topmenu")) ? ucfirst($module) : Config::get("{$module}.topmenu"));
                                        if ($menu_link !== false) :
                                            if (method_exists($module . "Service", "navigation")) : ?>
                                                <li class="nav-item dropdown <?php echo $w->_module == $module ? 'active' : ''; ?>" id="topnav_<?php echo $module; ?>">
                                                    <a class="nav-link dropdown-toggle caret-off" href="#" id="topnav_<?php echo $module; ?>_dropdown_link" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo ucfirst($module); ?>
                                                    </a>
                                                    <?php // Try and get a badge count for the menu item
                                                    // echo $menu_link;
                                                    $module_service = ucfirst($module) . "Service";
                                                    $module_navigation = $module_service::getInstance($w)->navigation($w);

                                                    // Invoke hook to inject extra navigation
                                                    $hook_navigation_items = $w->callHook($module, "extra_navigation_items", $module_navigation);
                                                    if (!empty($hook_navigation_items)) {
                                                        foreach ($hook_navigation_items as $hook_navigation_item) {
                                                            if (is_array($hook_navigation_item)) {
                                                                $module_navigation = array_merge($module_navigation, $hook_navigation_item);
                                                            } else {
                                                                $module_navigation[] = $hook_navigation_item;
                                                            }
                                                        }
                                                    } ?>
                                                    <div class="dropdown-menu" aria-labelledby="topnav_<?php echo $module; ?>_dropdown_link">
                                                        <?php echo implode('', $module_navigation); ?>
                                                    </div>
                                                </li>
                                            <?php else : ?>
                                                <li <?php echo $w->_module == $module ? 'class="active"' : ''; ?>><?php echo $menu_link; ?></li>
                                            <?php endif; ?>
                                            <!-- <li class="divider"></li> -->
                                        <?php endif;
                                    endif;
                                endforeach; ?>
                            </ul>
                            <!-- <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit">Search</button>
                            </form> -->
                        </div>
                    </nav>

                    <nav aria-label="breadcrumb" class="container" id="breadcrumbs">
                        <ol class="breadcrumb">
                        <?php
                        if (class_exists("History")) :
                            $breadcrumbs = History::get();

                            if (empty($breadcrumbs)) : ?>
                                <li class="breadcrumb-item active" aria-current="page">Your history will appear here</li>
                            <?php endif;
                            $isFirst = true && ($_SERVER['REQUEST_URI'] === key($breadcrumbs));
                            foreach ($breadcrumbs as $path => $value) :
                                if (!AuthService::getInstance($w)->allowed($path)) {
                                    continue;
                                }
                                if ($isFirst) : ?>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <span data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $value['name']; ?>" title='<?php echo $value['name']; ?>' ><?php echo $value['name']; ?></span>
                                    </li>
                                <?php else : ?>
                                    <li class="breadcrumb-item">
                                        <a href='<?php echo $path; ?>' data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $value['name']; ?>" title='<?php echo $value['name']; ?>' ><?php echo $value['name']; ?></a>
                                    </li>
                                <?php endif;
                                $isFirst = false;
                            endforeach;
                        endif; ?>
                        </ol>
                    </nav>
                </div>
                <div class="container">
                    <?php echo !empty($body) ? $body : ''; ?>
                </div>
            </div>
            <div id="menu-overlay" :class="{'active': showSidemenu}" @click="toggleMenu()"></div>
        </div>
        <?php $w->outputScripts(); ?>
    </body>
</html>