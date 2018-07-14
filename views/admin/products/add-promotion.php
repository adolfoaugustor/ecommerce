<form id="addPromotionForm" method="post">
    <div class="card mt-4">
        <h5 class="card-header">
            <?php echo sprintf('%s - %s', $this->product->name, $this->product->getPrice()) ?>
        </h5>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-6">
                    <img
                         src="<?php echo !empty($this->product->image) ? $this->product->image : '' ?>"
                         alt="<?php echo $this->product->name ?>"
                         style="max-width:100%">
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="<?php echo !empty($this->promotion->start_date) ? dateEN2BR($this->promotion->start_date) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="<?php echo !empty($this->promotion->end_date) ? dateEN2BR($this->promotion->end_date) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" id="price" name="price" class="form-control" value="<?php echo number_format($this->promotion->price, 2, ',', '') ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" type="submit">Create</button>
        </div>
    </div>
</form>
