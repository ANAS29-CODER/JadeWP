<?php

$view             = $view ?? [];
$isHasDemoObjects = $view['isHasDemoObjects'] ?? false;
$formNonce        = $view['formNonce'] ?? '';
$formMessage      = $view['formMessage'] ?? '';

?>

<form action="" method="post" class="av-dashboard">
    <input type="hidden" name="_av-demo-import" value="_av-demo-import">
    <input type="hidden" name="_wpnonce"
           value="<?php
           echo esc_attr($formNonce) ?>">

    <div class="av-dashboard__main">

        <?php
        if ($formMessage) { ?>
            <div class="av-introduction av-dashboard__block av-dashboard__block--medium">
                <?php
                echo $formMessage;
                if ($isHasDemoObjects) {
                    ?>
                    <br><br>
                    <button class="button button-primary button-large av-dashboard__button av-dashboard__button--red"
                            name="_delete">
                        Delete imported objects
                    </button>
                    <?php
                } ?>
            </div>
            <?php
        } ?>

        <?php
        if (! $isHasDemoObjects){ ?>
        <div class="av-introduction av-dashboard__block">
            <p class="av-introduction__title">Import Demo to get started in seconds</p>
            <p class="av-introduction__description">
                Whether you're new to ACF Views or you just want to get the basic setup quickly then this tool will help
                you with the following scenarios:<br><br>
            </p>
            <p><b>Display page's ACF fields on the same page</b></p>
            <ol class="av-introduction__description av-introduction__ol">
                <li>Create 'draft' pages for "Samsung Galaxy A53", "Nokia X20" and "Xiaomi 12T".</li>
                <li>Create an ACF Field Group called "Phone" with location set to those pages.</li>
                <li>Create an ACF View called "Phone" with fields assigned from the "Phone" Field Group.</li>
                <li>Fill each page’s ACF fields with text and add the ACF View shortcode to the page content.</li>
            </ol>
            <p><b>Display a specific post, page or CPT item with its fields</b></p>
            <ol class="av-introduction__description av-introduction__ol">
                <li>Create a 'draft' page called "Article about Samsung"</li>
                <li>Add the ACF View shortcode to the page content with "object-id" argument to "Samsung Galaxy A53".
                </li>
            </ol>
            <p><b>Display specific posts, pages or CPT items and their fields by using filters <br>or by manually
                    assigning
                    items</b></p>
            <ol class="av-introduction__description av-introduction__ol">
                <li>Create an ACF Card for "List of Phones" with ACF View "Phone" assigned and filtered to.
                </li>
                <li>Create a 'draft' page called "Most popular phones in 2022" and add the ACF Card shortcode to the
                    page content.
                </li>
            </ol>

            <p class="av-introduction__description">
                <br> Press the Import button and wait a few seconds.<br><br>
                When the process has completed, you'll see links to all the items for quick editing.<br><br>
                <b>Note: After the import, a delete button will appear, that can be used to remove the imported
                    items.</b><br><br>
            </p>
            <button class="button button-primary button-large" name="_import">Import demo now</button>
            <?php
            } ?>
        </div>
    </div>
</form>
