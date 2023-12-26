<?php
$ticket_per_page    = get_option( 'posts_per_page', 10 );
//$ticket_per_page    = 2;

$ticket_page        = isset( $_GET['ticket_page'] ) ? absint( $_GET['ticket_page'] ) : 1;

$keyword            = isset( $_GET['ticket_search'] ) ? sanitize_text_field( $_GET['ticket_search'] ) : '';
$priority           = isset( $_GET['priority'] ) && $_GET['priority'] != 'all' ? sanitize_text_field( $_GET['priority'] ) : '';
$status             = isset( $_GET['status'] ) && $_GET['status'] != 'all' ? sanitize_text_field( $_GET['status'] ) : '';

$args               = [
    'post_parent'       => 0,
    'post_type'         => 'ticket',
    'post_status'       => $status ? $status : ['close', 'pending', 'answer'],
    'posts_per_page'    => $ticket_per_page,
    'paged'             => $ticket_page,
    's'                 => $keyword,
];

if( $priority ){
    $args['meta_key']   = '_priority';
    $args['meta_value'] = $priority;
}

$is_operator        = daneshjooyar_user_can_manage_tickets();

if( ! $is_operator ){
    $args['author'] = get_current_user_id();
}

$ticket_query   = new WP_Query( $args );
//$pages          = ceil( $ticket_query->found_posts / $ticket_per_page );

$statsus    = [
    'close'     => '',
    'pending'   => 'badge-warning',
    'answer'    => 'badge-success',
];

$priorities= [
    'low'       => '',
    'normal'    => 'badge-info',
    'high'      => 'badge-danger',
];

$deps= [
    'support'       => 'پشتیبانی',
    'sale'          => 'فروش',
    'content'      => 'محتوا',
];


$unread_message = 0;
if( $is_operator ){
    $unread_message = daneshjooyar_panel_get_pending_ticket_count();
}

?>
<?php daneshjooyar_panel_header();?>
<div class="panel-container">
<?php daneshjooyar_panel_sidebar();?>
    <main>
        <div class="panel-title">
            <h1>تیکت ها</h1>
        </div>
        <div class="panel-content">
            <div class="widget widget-tickets">
                <header>
                    <svg id="user-cirlce-add" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <g id="Group" transform="translate(6.27 8.11)">
                            <path id="Vector" d="M5.62,2.81A2.81,2.81,0,1,1,2.81,0,2.81,2.81,0,0,1,5.62,2.81Z" transform="translate(2.38)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                            <path id="Vector-2" data-name="Vector" d="M10.38,4.23C10.38,1.9,8.06,0,5.19,0S0,1.89,0,4.23" transform="translate(0 7.86)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </g>
                        <path id="Vector-3" data-name="Vector" d="M9.5,0a9.509,9.509,0,0,1,3.7.74A4.054,4.054,0,0,0,13,2a3.921,3.921,0,0,0,.58,2.06,3.684,3.684,0,0,0,.76.91A3.921,3.921,0,0,0,17,6a3.686,3.686,0,0,0,1.25-.21A9.5,9.5,0,1,1,5.56.85" transform="translate(2 3)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-4" data-name="Vector" d="M8,4a3.594,3.594,0,0,1-.12.93,3.734,3.734,0,0,1-.46,1.13A3.9,3.9,0,0,1,5.25,7.79,3.686,3.686,0,0,1,4,8,3.921,3.921,0,0,1,1.34,6.97a3.684,3.684,0,0,1-.76-.91A3.921,3.921,0,0,1,0,4,4.054,4.054,0,0,1,.2,2.74a3.945,3.945,0,0,1,.93-1.53A4,4,0,0,1,4,0,3.944,3.944,0,0,1,6.97,1.33,3.984,3.984,0,0,1,8,4Z" transform="translate(15 1)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <g id="Group-2" data-name="Group" transform="translate(17.51 3.52)">
                            <path id="Vector-5" data-name="Vector" d="M2.98,0H0" transform="translate(0 1.46)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                            <path id="Vector-6" data-name="Vector" d="M0,0V2.99" transform="translate(1.49)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        </g>
                        <path id="Vector-7" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
                    </svg>
                    <h2>آخرین تیکت ها</h2>
                    <?php if( $unread_message ):?>
                    <span class="badge badge-danger"><?php echo $unread_message;?> پیام جدید</span>
                    <?php endif;?>
                    <a href="<?php echo daneshjooyar_panel_url('ticket/new');?>" class="btn btn-secondary btn-ticket-new">
                        + تیکت جدید
                    </a>
                </header>
                <div class="widget-body">
                    <form action="" id="ticket-search">
                        <div class="ticket-filters">
                            <div class="form-group">
                                <label for="search" class="label-hidden">جستجو</label>
                                <input type="search" value="<?php echo esc_attr( $keyword );?>" id="search" name="ticket_search" placeholder="جستجو" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="priority" class="label-hidden">اهمیت</label>
                                <select name="priority" id="priority" class="form-control">
                                    <option value="all" <?php selected( ! $priority );?>>همه اولویت ها</option>
                                    <option value="low" <?php selected( $priority, 'low' );?>>پایین</option>
                                    <option value="normal" <?php selected( $priority, 'normal' );?>>متوسط</option>
                                    <option value="high" <?php selected( $priority, 'high' );?>>بالا</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status" class="label-hidden">وضعیت</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="all" <?php selected( $status );?>>همه وضعیت ها</option>
                                    <option value="close" <?php selected( $status, 'close' );?>>بسته شده</option>
                                    <option value="pending" <?php selected( $status, 'pending' );?>>در انتظار پاسخ</option>
                                    <option value="answer" <?php selected( $status, 'answer' );?>>پاسخ داده شده</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary">فیلتر کردن تیکت ها</button>
                    </form>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان تیکت</th>
                            <?php if( $is_operator ):?>
                            <th>ارسال کننده</th>
                            <?php endif;?>
                            <th>وضعیت</th>
                            <th>اهمیت</th>
                            <th>دپارتمان</th>
                            <th>آخرین بروزرسانی</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if( $ticket_query->have_posts() ):?>
                                <?php while( $ticket_query->have_posts() ):$ticket_query->the_post();?>
                                    <?php
                                    global $post;
                                    $ticket     = $post;
                                    $priority   = get_post_meta( $ticket->ID, '_priority', true );
                                    $dep        = get_post_meta( $ticket->ID, '_department', true );
                                    $user       = get_user_by( 'ID', $ticket->post_author );
                                    $name       = trim( $user->first_name . ' ' . $user->last_name );
                                    if( ! $name ){
                                        $name = $user->display_name;
                                    }
                                    ?>
                                    <tr>
                                        <td>1</td>
                                        <td><?php the_title();?></td>
                                        <?php if( $is_operator ):?>
                                        <td>
                                            <?php echo $name;?>
                                        </td>
                                        <?php endif;?>
                                        <td>
                                            <span class="badge <?php echo isset( $statsus[$ticket->post_status] ) ? $statsus[$ticket->post_status] : '';?>">
                                                <?php echo daneshjooyar_panel_translate_ticket_status( $ticket->post_status );?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo isset( $priorities[$priority] ) ? $priorities[$priority] : '';?>">
                                                <?php echo daneshjooyar_panel_translate_ticket_priority( $priority );?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo isset( $deps[$dep] ) ? $deps[$dep] : '-';?>
                                        </td>
                                        <td class="ltr"><?php echo date_i18n( 'Y/m/d - H:i', strtotime( $ticket->post_modified ) );?></td>
                                        <td><a href="<?php echo daneshjooyar_panel_url( 'ticket/view?ticket_id=' . $ticket->ID );?>" class="btn btn-outline">مشاهده</a></td>
                                    </tr>
                                <?php endwhile;?>
                            <?php else:?>
                                <tr>
                                    <td colspan="<?php echo $is_operator ? 8 : 7;?>">
                                        تیکتی یافت نشد
                                    </td>
                                </tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                    <div class="pagination">
                    <?php
                    $base_url = daneshjooyar_panel_url( 'ticket/?ticket_page=%#%' );
                    echo paginate_links( array(
                        'base'      => $base_url,
                        'format'    => '?ticket_page=%#%',
                        'current'   => $ticket_page,
                        'total'     => $ticket_query->max_num_pages
                    ) );
                    ?>
                    </div>
                </div>
            </div>
        </div><!--.panel-content-->
    </main>
</div>
<?php daneshjooyar_panel_footer();?>