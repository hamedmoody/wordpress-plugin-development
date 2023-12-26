<div class="dk  ">
    <div class="dk-right ">
        <div class="dk-colors">
			<?php
			foreach ( $product->colors as $color ):
				?>
                <div class="dk-color" style="background-color: <?php echo $color->hex_code ?>">
                    <p class="p10"><?php echo $color->title ?></p></div>
			<?php endforeach; ?>
        </div>
        <div class="dk-thumbnail">
            <img src="<?php echo $product->images->main->url[0]; ?>"
                 alt="<?php echo $product->title; ?>">
        </div>
        <div class="dk-thumbnails">
            <img class="active" src="<?php echo $product->images->main->url[0]; ?>"
                 alt="<?php echo $product->title; ?>">
			<?php
			$counter = 0;
			foreach ( $product->images->list as $th ):
				if ( $counter < 10 ) {
					?>
                    <img src="<?php echo $th->url[0]; ?>"
                         alt="<?php echo $product->title; ?>">
					<?php
					$counter ++;
				}
				?>
			<?php endforeach; ?>
        </div>
    </div>
    <div class="dk-left ">
        <h2><?php echo esc_html( $product->title_fa ); ?></h2>
        <div class="dk-description">
            <p>
				<?php echo esc_html( $product->review->description ) ?>
            </p>
            <div class="dk-price">
                <div class="dk-right">
					<?php
					$price      = $product->default_variant->price->rrp_price;
					$sale_price = $product->default_variant->price->selling_price;
					$dis        = $price == $sale_price ? 0 : round( ( $price - $sale_price ) / $price * 100 );
					if ( $dis ) {
						?>
                        <span class="dk-discount"><?php
							echo $dis . '%';
							?>
		                   </span>
                        <del><?php echo number_format( $product->default_variant->price->rrp_price / 10 ) ?></del>
						<?php
					}
					?>
                    <ins><?php echo number_format( $product->default_variant->price->selling_price / 10 ) ?></ins>
                    <span class="dk-currency"> تومان</span>
                </div>
                <a href="<?php echo esc_html( 'https://www.digikala.com' . $product->url->uri ) ?>">
                    مشاهده و خرید
                </a>
            </div>
        </div>
    </div>
</div>