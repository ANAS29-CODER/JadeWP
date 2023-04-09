<?php

$view = $view ?? [];
$createAcfViewLink = $view['createAcfViewLink'] ?? '';
$createAcfCardLink = $view['createAcfCardLink'] ?? '';
$supportedFieldTypes = $view['supportedFieldTypes'] ?? '';
$supportBlock = $view['supportBlock'] ?? '';
$reviewBlock = $view['reviewBlock'] ?? '';
$pluginsVersion = $view['pluginsVersion'] ?? '';
// $proBanner = $view['proBanner'] ?? '';
$proBanner = '';
$demoImportLink = $view['demoImportLink'] ?? '';
$videoReview = $view['videoReview'] ?? '';
$acfPluginInstallLink = $view['acfPluginInstallLink'] ?? '';
?>
<div class="av-dashboard">
    <div class="av-dashboard__main">
        <div class="av-introduction av-dashboard__block">
            <p class="av-introduction__title">ACF Views for WordPress</p>
            <div class="av-introduction__description">
                The plugin allows to display selected ACF fields or Posts anywhere using shortcodes, HTML markup is
                created automatically by the plugin.
            </div>
        </div>

        <div class="av-introduction av-dashboard__block">
            <p class="av-introduction__title">How it works</p>
            <div class="av-introduction__description">
                <b>View for ACF fields</b><br>
                <a href="https://docs.acfviews.com/guides/acf-views/basic/creating-an-acf-view" target="_blank">Create a
                    View</a> and assign one or more custom fields, our plugin then
                generates a shortcode that you’ll
                use to display the field values to users. Style the output with the CSS field included in every View.
                <br><br>
                <b>Card for post selections</b><br>
                <a href="https://docs.acfviews.com/guides/acf-cards/basic/creating-an-acf-card" target="_blank">Create a
                    Card</a> and assign posts (or CPT items), choose a View (that will
                be used to display each item)
                and our plugin generates a shortcode that you’ll use to display the set of posts. The list of posts can
                be assigned manually or dynamically with filters.
            </div>
        </div>

        <div class="av-introduction av-dashboard__block">
            <p class="av-introduction__title">Import Demo to get started in seconds</p>
            <div class="av-introduction__description">
                Whether you’re new to ACF Views or you just want to get the basic setup quickly then try a <a
                        href="<?php
                        echo esc_attr($demoImportLink) ?>">demo import</a>.
            </div>
        </div>
    </div>
    <div class="av-dashboard__side">
        <div class="av-dashboard__side-block">
            <p>Plugin's version is <b><?php
                    echo esc_html($pluginsVersion) ?></b></p>
        </div>
        <div class="av-dashboard__side-block">
            <p class="av-dashboard__title">Having issues?</p>
            <?php
            echo $supportBlock;
            ?>
        </div>
        <div class="av-dashboard__side-block">
            <p class="av-dashboard__title">Rate & review</p>
            <?php
            list($currentView, $view) = [$view, $reviewBlock];
            include __DIR__ . '/../postbox/review.php';
            $view = $currentView;
            ?>
        </div>
        <?php
        if ($proBanner) { ?>
            <div class="av-dashboard__side-block">
                <?php
                list($currentView, $view) = [$view, $proBanner];
                include __DIR__ . '/../postbox/pro-banner.php';
                $view = $currentView;
                ?>
            </div>
            <?php
        } ?>
    </div>
</div>
