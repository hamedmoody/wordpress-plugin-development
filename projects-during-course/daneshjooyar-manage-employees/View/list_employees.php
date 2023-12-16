<?php
global $title;
global $employee_list_table;
$country = 'ایران';
$city = 'تهران';
$text = sprintf(
	/** translators: 1 => city & 2 => country, thanks you */
	__( 'I am from %1$s in country %2$s', 'daneshjooyar-manage-employees' ),
	$city,
	$country
);
echo $text;
?> 
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo $title;?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=dyme_employees_create' );?>" class="page-title-action">New employee</a>
	<form method="GET">
		<input type="hidden" name="page" value="dyme_employees"/>
		<?php
		$employee_list_table->views();
		$employee_list_table->search_box( 'Search employee', 'employee_search' );
		$employee_list_table->display();
		?>
	</form>
</div>