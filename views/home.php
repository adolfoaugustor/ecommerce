<div class="starter-template">
    <h1>Product List</h1>

    <?php if (count($this->products)) : ?>
        <div class="row">
            <?php foreach ($this->products as $product) : ?>
                <?php $promotion = $product->latestPromotion(); ?>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <a class="homeProductImage"
                           style="background-image:url(<?php echo !empty($product->image) ? $product->image : '' ?>);"
                           href="<?php echo '/products/'.$product->slug ?>"></a>
                        <div class="card-body">
                            <p class="card-text">
                                <?php echo $product->name ?>
                            </p>
                            <p class="card-text">
                                <?php
                                    if (!empty($promotion)) {
                                        echo sprintf('<small class="scratched">%s</small><br/><span>%s (%s)</span>', $product->getPrice(), $promotion->getPrice(), calcPercent($product->price, $promotion->price).'%');
                                    } else {
                                        echo sprintf('<span>%s</span>', $product->getPrice());
                                    }
                                ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?php echo date('d/m/Y H:i:s', strtotime($product->updated_at)) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No products</p>
    <?php endif; ?>
</div>
