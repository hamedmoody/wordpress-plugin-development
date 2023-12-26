<?php
defined('ABSPATH') || exit;

?>
<?php daneshjooyar_panel_header();?>
<div class="panel-container">
<?php daneshjooyar_panel_sidebar();?>
  <main>
    <div class="panel-title">
      <h1>تیکت جدید</h1>
    </div>
    <div class="panel-content">
      <div class="widget widget-tickets">
        <div class="widget-body">
          <form id="new-ticket" class="new-ticket-form">
            <div class="new-ticket-info-fields">
              <div class="form-group">
                <label for="title">عنوان تیکت:</label>
                <input type="text" placeholder="نام تیکت شما" name="title" id="title" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="department">واحد مربوطه:</label>
                <select name="department" id="department" class="form-control">
                  <option value="support">پشتیبانی</option>
                  <option value="sale">فروش</option>
                  <option value="content">محتوا</option>
                </select>
              </div>
              <div class="form-group">
                <label for="priority">درجه اهمیت:</label>
                <select name="priority" id="priority" class="form-control">
                  <option value="low">پایین</option>
                  <option value="normal">معمولی</option>
                  <option value="high">بالا</option>
                </select>
              </div>
            </div><!--.new-ticket-info-fields-->
            <div class="form-group">
              <label for="content">متن پیام تیکت:</label>
              <textarea name="content" id="content" class="form-control" cols="30" rows="5" placeholder="متن تیکت شما..." required></textarea>
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
              <input type="hidden" name="ID" value="0">
              <?php wp_nonce_field( 'daneshjooyar_panel_new_ticket' . 0 );?>
              <button class="btn btn-primary">ارسال تیکت</div>
            </div>
          </form>
        </div>
      </div>
    </div><!--.panel-content-->
  </main>
</div>
<?php daneshjooyar_panel_footer();?>