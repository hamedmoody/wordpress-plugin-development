<?php
defined('ABSPATH') || exit;
$ticket_id = isset( $_GET['ticket_id'] ) ? absint( $_GET['ticket_id'] ) : 0;
if( ! $ticket_id ) {
  wp_safe_redirect( daneshjooyar_panel_url( 'ticket' ) );
  exit;
}
$ticket   = get_post( $ticket_id );
if( ! $ticket ){
  wp_safe_redirect( daneshjooyar_panel_url( 'ticket' ) );
  exit;
}

if(
  $ticket->post_author != get_current_user_id()
  &&
  ! daneshjooyar_user_can_manage_tickets(  )
){
  wp_safe_redirect( daneshjooyar_panel_url( 'ticket' ) );
  exit;
}

$replies  = get_posts([
  'post_type'       => 'ticket',
  'post_parent'     => $ticket->ID,
  'posts_per_page'  => -1,
  'post_status'     => 'any'
]);

$tickets  = array_merge( $replies, [$ticket] );


?>
<?php daneshjooyar_panel_header();?>
<div class="panel-container">
<?php daneshjooyar_panel_sidebar();?>
  <main>
    <div class="panel-title">
      <h1>
        تیکت: <?php echo get_the_title( $ticket );?>
      </h1>
      <?php if( $ticket->post_status != 'close' ):?>
      <a href="#" id="close-ticket" class="btn btn-primary" data-id="<?php echo $ticket->ID;?>" data-nonce="<?php echo wp_create_nonce( 'ticket-status' . $ticket->ID );?>">بستن تیکت</a>
      <?php endif;?>
    </div>
    <div class="panel-content">
      <?php if( $ticket->post_status == 'close' ):?>
      <p class="ticket-closed">
        <span>
          این تیکت بسته شده است، برای بازگشایی مجدد به آن کافیت پاسخی ارسال کنید
        </span>
      </p>
      <?php endif;?>
      <div class="ticket-messages">
        <?php foreach( $tickets as $ticket_item ):?>
          <?php
          $files       = get_post_meta( $ticket_item->ID, '_file' );
          ?>
        <div class="ticket-message <?php echo $ticket->post_author == $ticket_item->post_author ? 'ticket-message-left' : '';?>">
          <?php echo get_avatar( $ticket_item->post_author, 64 );?>
          <!-- <img src="assets/images/hamedmoody.jpg" alt="حامد مودی" width="64" height="64"> -->
          <div class="ticket-message-box">
            <a href="#content" class="ticket-reply"></a>
            <div class="ticket-message-text">
              <?php echo wpautop( get_the_content( null, false, $ticket_item ) );?>
            </div>
            <div class="ticket-message-footer">
              <span class="ticket-date">
                <?php echo date_i18n( 'Y/m/d H:i', strtotime( $ticket_item->post_date ) );?>
              </span>
              <?php if( $files ):?>
              <div class="ticket-files">
                <?php foreach( $files as $file ):?>
                  <?php 
                    $file_name  = '';
                    if( $file ){
                      $file_info = pathinfo( $file );
                      $file_name = $file_info['basename'];
                    }
                  ?>
                  <a href="<?php echo esc_url( $file );?>" class="ticket-attachment" download title="<?php echo esc_attr( $file_name );?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="document-download" transform="translate(-300 -188)">
                      <path id="Vector" d="M0,0V2.65l2-2" transform="translate(309 202.35)" fill="none" stroke="#4a58fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-2" data-name="Vector" d="M0,0V1" transform="translate(309 199)" fill="none" stroke="#4a58fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-3" data-name="Vector" d="M2,2,0,0" transform="translate(307 203)" fill="none" stroke="#4a58fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-4" data-name="Vector" d="M0,7C0,2,2,0,7,0h5" transform="translate(302 190)" fill="none" stroke="#4a58fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-5" data-name="Vector" d="M20,0V5c0,5-2,7-7,7H7c-5,0-7-2-7-7V2.98" transform="translate(302 198)" fill="none" stroke="#4a58fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-6" data-name="Vector" d="M4,8C1,8,0,7,0,4V0L8,8" transform="translate(314 190)" fill="none" stroke="#4a58fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-7" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(300 188)" fill="none" opacity="0"/>
                    </g>
                  </svg>
                  
                </a>
                <?php endforeach;?>
              </div>
              <?php endif;?>
            </div>
          </div>
        </div><!--.ticket-message-->
        <?php endforeach;?>
      </div><!--.ticket-messages-->
      <div class="widget widget-tickets">
        <div class="widget-body">
          <form id="new-ticket" class="new-ticket-form">
            <div class="form-group">
              <label for="content">متن پاسخ تیکت:</label>
              <textarea name="content" id="content" class="form-control" cols="30" rows="5" placeholder="پاسخ تیکت شما..." rquired></textarea>
            </div>
            <div class="upload-files">
              
            </div><!--.upload-files-->
            <div class="upload-here">
              <label for="upload">
                + آپلود فایل
              </label>
              <input type="file" name="upload" id="upload" data-nonce="<?php echo wp_create_nonce( 'upload-file' );?>">
            </div>
            <div class="form-group">
              <input type="hidden" name="action" value="daneshjooyar_panel_new_ticket">
              <input type="hidden" name="ID" value="<?php echo $ticket->ID;?>">
              <?php wp_nonce_field( 'daneshjooyar_panel_new_ticket' . $ticket->ID );?>
              <button class="btn btn-primary">ارسال پاسخ</button>
            </div>
          </form>
        </div>
      </div>
    </div><!--.panel-content-->
  </main>
</div>
<?php daneshjooyar_panel_footer();?>