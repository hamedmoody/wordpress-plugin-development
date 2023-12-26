<?php
$is_operator = daneshjooyar_user_can_manage_tickets();
?>
<?php daneshjooyar_panel_header();?>
    <div class="panel-container">
        <?php daneshjooyar_panel_sidebar();?>
        <main>
            <h1>پیشخان</h1>
            <div class="panel-content">
                <?php if( $is_operator ): ?>
                <div class="dashboard-user-widgets">
                    <?php include( DANESHJOOYAR_PANEL_VIEW . 'widgets/user-chart.php' );?>
                    <?php include( DANESHJOOYAR_PANEL_VIEW . 'widgets/last-users.php' );?>
                </div><!--.dashboard-user-widgets-->
                <?php endif;?>
                <?php include( DANESHJOOYAR_PANEL_VIEW . 'widgets/last-tickets.php' );?>
            </div><!--.panel-content-->
        </main>
    </div><!--.panel-container-->
<?php daneshjooyar_panel_footer();?>