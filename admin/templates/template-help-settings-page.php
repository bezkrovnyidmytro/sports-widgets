<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

if (empty($args)) {
    return;
}
?>
    <div class="vcsw-wrap" id="help-page-section">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-settings-wrapper help-page">
            <?php echo ! empty($args['page_content']) ? $args['page_content'] : ''; ?>
            <a href="#help-page-section" class="vcsw-link-top">
                <svg height="800px" width="800px"
                     viewBox="0 0 330 330" xml:space="preserve">
                    <path id="XMLID_29_" d="M100.606,100.606L150,51.212V315c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15V51.212l49.394,49.394
                    C232.322,103.535,236.161,105,240,105c3.839,0,7.678-1.465,10.606-4.394c5.858-5.857,5.858-15.355,0-21.213l-75-75
                    c-5.857-5.858-15.355-5.858-21.213,0l-75,75c-5.858,5.857-5.858,15.355,0,21.213C85.251,106.463,94.749,106.463,100.606,100.606z"/>
                </svg>
            </a>
        </div>
    </div>
<?php

