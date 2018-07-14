<div class="card mt-4">
    <h5 class="card-header">
        Products
    </h5>
    <div class="card-body">

        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Image</th>
                    <th>Price</th>
<!--                    <th>description</th>-->
<!--                    <th>weight</th>-->
<!--                    <th>height</th>-->
<!--                    <th>width</th>-->
<!--                    <th>length</th>-->
<!--                    <th>diameter</th>-->
                    <th>Updated At</th>
                    <th>Actions</th>
<!--                    <th>created_at</th>-->
                </tr>
            </thead>
            <tbody>
                <?php foreach($this->products as $item) : ?>
                    <?php $promotion = $item->latestPromotion(); ?>
                    <tr>
                        <td><?php echo $item->id ?></td>
                        <td><?php echo $item->name ?></td>
                        <td><?php echo $item->slug ?></td>
                        <td>
                            <img src="<?php echo !empty($item->image) ? $item->image : '' ?>" alt="<?php echo $item->name ?>" height="100"/>
                        </td>
                        <td>
<!--                            --><?php //echo $item->getPrice() ?>
                            <?php
                                if (!empty($promotion)) {
                                    echo sprintf('<small class="scratched">%s</small><br/><span>%s (%s)</span>', $item->getPrice(), $promotion->getPrice(), calcPercent($item->price, $promotion->price).'%');
                                } else {
                                    echo sprintf('<span>%s</span>', $item->getPrice());
                                }
                            ?>
                        </td>
<!--                        <td>--><?php //echo $item->description ?><!--</td>-->
<!--                        <td>--><?php //echo $item->weight ?><!--</td>-->
<!--                        <td>--><?php //echo $item->height ?><!--</td>-->
<!--                        <td>--><?php //echo $item->width ?><!--</td>-->
<!--                        <td>--><?php //echo $item->length ?><!--</td>-->
<!--                        <td>--><?php //echo $item->diameter ?><!--</td>-->
                        <td><?php echo date('d/m/Y H:i:s', strtotime($item->updated_at)) ?></td>
<!--                        <td>--><?php //echo $item->created_at ?><!--</td>-->
                        <td>
                            <a class="btn btn-primary btn-sm" href="/admin/products/<?php echo $item->slug ?>/add-promotion">
                                Add Promotion
                            </a>
                            <a class="btn btn-danger btn-sm productRemove" href="/admin/products/<?php echo $item->slug ?>/remove">
                                Remove
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <div class="card-footer">&nbsp;</div>
</div>
