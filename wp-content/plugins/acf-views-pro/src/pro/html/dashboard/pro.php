<?php

$view = $view ?? [];
$formNonce = $view['formNonce'] ?? '';
$formMessage = $view['formMessage'] ?? '';
$license = $view['license'] ?? '';
$licenseExpiration = $view['licenseExpiration'] ?? '';
$licenseUsedDomains = $view['licenseUsedDomains'] ?? '';

if ($licenseExpiration) {
    $licenseExpiration = DateTime::createFromFormat('Ymd', $licenseExpiration);
    $licenseExpiration = $licenseExpiration ?
        $licenseExpiration->format('d-m-Y') :
        '';
}

$activateButtonLabel = !$licenseExpiration ?
    'Activate' :
    'Update';

?>

<form action="" method="post" class="av-dashboard">
    <input type="hidden" name="_av-pro" value="_av-pro">
    <input type="hidden" name="_wpnonce"
           value="<?php
           echo esc_attr($formNonce) ?>">

    <div class="av-dashboard__main">

        <?php
        if ($formMessage) { ?>
            <div class="av-introduction av-dashboard__block av-dashboard__block--medium">
                <?php
                echo $formMessage; ?>
            </div>
            <?php
        } ?>


        <div class="av-introduction av-dashboard__block">
            <p class="av-introduction__title">Activate Pro License Key</p>
            <p class="av-introduction__description">
                To receive automatic updates, including compatibility and security updates, please insert your license
                key. <br><br>
            </p>

            <div class="av-license">
                <span class="av-license__label">License Key:</span>
                <div>
                    <input class="av-license__input" type="text" name="_license" value="<?php
                    echo $license ?>" placeholder="XXXX-XXXX-XXXX-XXXX">
                    <p class="av-introduction__description av-introduction__description--type--light">
                        The key was sent to your email from WPLake.org
                    </p>
                </div>
            </div>

            <div class="av-license">
                <span class="av-license__label">Status:</span>
                <?php
                if ($licenseExpiration) { ?>
                    <span class="av-license__description av-introduction__description av-introduction__description--type--success">
                        You’re currently receiving automatic updates. License expires on: <b><?php
                            echo $licenseExpiration ?></b>. Used on websites: <b><?php
                            echo $licenseUsedDomains ?></b>.
                    </span>
                    <?php
                } else { ?>
                    <span class="av-license__description av-introduction__description av-introduction__description--type--fail">
                        Automatic updates are unavailable.
                    </span>
                    <?php
                } ?>
            </div>

            <br> <br>

            <div style="display: flex;justify-content: space-between;max-width: 200px;">
                <button class="button button-primary button-large av-dashboard__button" name="_activate"><?php
                    echo esc_html($activateButtonLabel) ?>
                </button>
                <!-- <?php
                // don't allow to deactivate (need to put new or leave empty)
                // because deactivation doesn't remove the domain from the list on the update server
                /*                if ($licenseExpiration) { */ ?>
                    <button class="button button-primary button-large av-dashboard__button av-dashboard__button--red"
                            name="_deactivate">Deactivate
                    </button>
                    --><?php
                /*                }*/ ?>
            </div>


            <br> <br>

            <p class="av-introduction__description av-introduction__description--type--light">
                <?php
                if ($licenseExpiration) { ?>
                    <span>'Status' information is saved locally. Press the 'Update' button to refresh.</span>
                    <br>
                    <span>If you want to detach this website from your license please <a
                                href="https://wplake.org/acf-views-support/"
                                target="_blank">contact
                        us</a>.</span>
                    <?php
                } else { ?>
                    <span>If you’re having problems activating your license key please <a
                                href="https://wplake.org/acf-views-support/"
                                target="_blank">contact
                        us</a>.</span>
                    <?php
                } ?>
            </p>
        </div>
    </div>
</form>
