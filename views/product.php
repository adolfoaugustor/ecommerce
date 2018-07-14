<?php $promotion = $this->product->latestPromotion(); ?>
<div class="row mt-4">
    <div class="col-md-4 productImage">
        <a class="image"
             href="<?php echo !empty($this->product->image) ? $this->product->image : 'javascript:void(0);' ?>"
             target="_blank"
             style="background-image:url(<?php echo !empty($this->product->image) ? $this->product->image : '' ?>)">
        </a>
    </div>
    <div class="col-md-8 productDetails">
        <h5 class="card-title"><?php echo $this->product->name ?></h5>
        <p class="card-text"><?php echo $this->product->description ?></p>
        <p class="card-text">
            Weight: <?php echo $this->product->weight ?><br/>
            Height: <?php echo $this->product->height ?><br/>
            Width: <?php echo $this->product->width ?><br/>
            Length: <?php echo $this->product->length ?><br/>
            Diameter: <?php echo $this->product->diameter ?><br/>
            Updated At: <?php echo $this->product->updated_at ?>
            <!--Created At: <?php /*echo $this->product->created_at*/ ?>-->
        </p>

        <div class="d-flex justify-content-between">
            <h3 class="p-6">
                <?php
                    if (!empty($promotion)) {
                        echo sprintf('<small class="scratched">%s</small><br/><span>%s (%s)</span>', $this->product->getPrice(), $promotion->getPrice(), calcPercent($this->product->price, $promotion->price).'%');
                    } else {
                        echo sprintf('<span>%s</span>', $this->product->getPrice());
                    }
                ?>
            </h3 class="p-6">
            <button type="button" class="btn btn-primary btnBookNow">Buy Now</button>
        </div>

        <form id="freteCalculateForm" class="mt-4">
            <h5 class="card-title">Frete Calculate</h5>
            <div class="form-group">
                <input type="text" class="form-control" name="cep" placeholder="CEP">
                <input type="hidden" name="product_slug" value="<?php echo $this->product->slug ?>" />
            </div>
            <div>
                <button class="btn btn-primary mx-sm-1" type="submit">Calculate</button>
            </div>
            <small class="message mx-sm-1"></small>
        </form>
    </div>
</div>

<div id="modalBookNow" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preencha os campos abaixo:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <?php
                    if (!empty($promotion)) {
                        echo sprintf('<small class="scratched">%s</small><br/><span>%s (%s)</span> + (FRETE)', $this->product->getPrice(), $promotion->getPrice(), calcPercent($this->product->price, $promotion->price).'%');
                    } else {
                        echo sprintf('<span>%s</span> + (FRETE)', $this->product->getPrice());
                    }
                    ?>
                </p>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit">Book Now</button>
            </div>
        </div>
    </div>
</div>
