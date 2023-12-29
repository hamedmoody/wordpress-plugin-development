<?php
defined('ABSPATH') || exit;
$author = isset( $_GET['author'] ) ? intval( $_GET['author'] ) : 0;
?>
<label for="student">
    <select name="author" id="student">
        <option <?php selected( ! $author );?>><?php esc_html_e( 'All students', 'daneshjooyar-course-shop' );?> </option>
        <?php foreach( dcs_get_students() as $student ):?>
        <option <?php selected( $author == $student->ID );?> value="<?php echo $student->ID;?>"><?php echo $student->display_name;?></option>
        <?php endforeach;?>
    </select>
</label>