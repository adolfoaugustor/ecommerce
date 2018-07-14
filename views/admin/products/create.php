<form id="productCreateForm" action="/admin/products" method="post" enctype="multipart/form-data">
    <div class="card mt-4">
        <h5 class="card-header">
            Criar
        </h5>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $this->product->name ?>">
                </div>
                <div class="form-group col-md">
                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" class="form-control" value="<?php echo number_format($this->product->price, 2, ',', '') ?>">
                </div>
                <div class="form-group col-md">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md">
                    <label for="weight">Weight (g)</label>
                    <input type="text" id="weight" name="weight" class="form-control" value="<?php echo $this->product->weight ?>">
                </div>
                <div class="form-group col-md">
                    <label for="height">Height</label>
                    <input type="text" id="height" name="height" class="form-control" value="<?php echo $this->product->height ?>">
                </div>
                <div class="form-group col-md">
                    <label for="width">Width</label>
                    <input type="text" id="width" name="width" class="form-control" value="<?php echo $this->product->width ?>">
                </div>
                <div class="form-group col-md">
                    <label for="length">Length</label>
                    <input type="text" id="length" name="length" class="form-control" value="<?php echo $this->product->length ?>">
                </div>
                <div class="form-group col-md">
                    <label for="diameter">Diameter</label>
                    <input type="text" id="diameter" name="diameter" class="form-control" value="<?php echo $this->product->diameter ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?php echo $this->product->description ?></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" type="submit">Create</button>
        </div>
    </div>
</form>
