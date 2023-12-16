<?php
defined( 'ABSPATH' ) || exit;
global $title;
$ID         = 0;
$first_name = '';
$last_name  = '';
$mission    = 0;
$birthdate  = '';
$weight     = 0;
$avatar     = '';
if( $employee ){
    $ID         = $employee->ID;
    $first_name       = $employee->first_name;
    $last_name     = $employee->last_name;
    $mission    = $employee->mission;
    $birthdate  = $employee->birthdate;
    $weight     = $employee->weight;
    $avatar     = $employee->avatar;
}
$attr = '" onclick="alert(321)';
?>
<h1><?php echo $first_name ? 'Edit employee ' . esc_html( $first_name . ' ' . $last_name ) : $title;?></h1>
<form action="" method="POST">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="first_name">Name</label>
                </th>
                <td>
                    <input type="text" name="first_name" title="<?php echo esc_attr($attr);?>" id="first_name" placeholder="<?php esc_attr_e( 'Your first name', 'daneshjooyar-manage-employees');?>" value="<?php echo esc_attr( $first_name );?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="last_name">Family</label>
                </th>
                <td>
                    <textarea name="last_name" id="last_name" cols="30" rows="10"><?php echo esc_textarea( $last_name );?></textarea>
                    <!-- <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $last_name );?>"> -->
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="mission">Mission count</label>
                </th>
                <td>
                    <input type="number" name="mission" id="mission" value="<?php echo esc_attr( $mission );?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="weight">Weight</label>
                </th>
                <td>
                    <input type="number" name="weight" step="0.1" id="weight" value="<?php echo esc_attr( $weight );?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="email">email</label>
                </th>
                <td>
                    <input type="email" name="email" id="email" value="">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="status">Status</label>
                </th>
                <td>
                    <select name="status" id="status">
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                        <option value="suspend">suspend</option>
                        <option value="delete">delete</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="birthdate">Birthdate</label>
                </th>
                <td>
                    <input type="date" name="birthdate" id="birthdate" value="<?php echo esc_attr( $birthdate );?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="avatar">Employee Avatar</label>
                </th>
                <td>
                    <input type="url" name="avatar" id="avatar" value="<?php echo esc_url( $avatar );?>">
                    <button type="button" class="button button-secondary" id="employee_avatr_select">انتخاب تصویر کارمند</button>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="hidden" name="ID" value="<?php echo esc_attr( $ID );?>">
        <?php wp_nonce_field( 'edit_employee' . $ID );?>
        <button class="button button-primary" name="save_employee">
            <?php echo $employee ? 'Edit employee' : 'Add employee';?>
        </button>
    </p>
</form>